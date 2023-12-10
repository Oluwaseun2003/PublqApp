<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\BasicSettings\Basic;
use Illuminate\Http\Request;
use App\Models\Organizer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Rules\MatchEmailRule;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Customer;
use App\Models\Event\Booking;
use App\Models\Event\Wishlist;
use App\Models\Language;
use App\Rules\MatchOldPasswordRule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Laravel\Socialite\Facades\Socialite;

class CustomerController extends Controller
{
  private $admin_user_name;
  public function __construct()
  {
    $admin = Admin::select('username')->first();
    $this->admin_user_name = $admin->username;
  }

  //index
  public function index()
  {
    $bookings = Booking::where('customer_id', Auth::guard('customer')->user()->id)->OrderBy('id', 'desc')->limit(10)->get();
    $language = Language::query()->where('is_default', '=', 1)->first();
    return view('frontend.customer.dashboard.index', compact('bookings'));
  }
  //signup
  public function signup()
  {
    return view('frontend.customer.signup');
  }
  //create
  public function create(Request $request)
  {

    $rules = [
      'fname' => 'required',
      'lname' => 'required',
      'email' => 'required|email|unique:customers',
      'username' => [
        'required',
        'alpha_dash',
        "not_in:$this->admin_user_name",
        Rule::unique('customers', 'username')
      ],
      'password' => 'required|confirmed|min:6',
    ];

    $info = Basic::select('google_recaptcha_status')->first();
    if ($info->google_recaptcha_status == 1) {
      $rules['g-recaptcha-response'] = 'required|captcha';
    }

    $messages = [];

    if ($info->google_recaptcha_status == 1) {
      $messages['g-recaptcha-response.required'] = 'Please verify that you are not a robot.';
      $messages['g-recaptcha-response.captcha'] = 'Captcha error! try again later or contact site admin.';
    }

    $request->validate($rules, $messages);

    $in = $request->all();
    $in['password'] = Hash::make($request->password);
    // first, generate a random string
    $randStr = Str::random(20);

    // second, generate a token
    $token = md5($randStr . $request->fname . $request->email);

    $in['verification_token'] = $token;

    // send a mail to user for verify his/her email address
    $this->sendVerificationMail($request, $token);
    Customer::create($in);

    return redirect()->route('customer.login');
  }
  public function sendVerificationMail(Request $request, $token)
  {
    // first get the mail template information from db
    $mailTemplate = MailTemplate::where('mail_type', 'verify_email')->first();
    $mailSubject = $mailTemplate->mail_subject;
    $mailBody = $mailTemplate->mail_body;

    // second get the website title & mail's smtp information from db
    $info = DB::table('basic_settings')
      ->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
      ->first();

    $link = '<a href=' . url("customer/signup-verify/" . $token) . '>Click Here</a>';

    $mailBody = str_replace('{username}', $request->fname . ' ' . $request->lname, $mailBody);
    $mailBody = str_replace('{verification_link}', $link, $mailBody);
    $mailBody = str_replace('{website_title}', $info->website_title, $mailBody);

    // initialize a new mail
    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    // if smtp status == 1, then set some value for PHPMailer
    if ($info->smtp_status == 1) {
      $mail->isSMTP();
      $mail->Host       = $info->smtp_host;
      $mail->SMTPAuth   = true;
      $mail->Username   = $info->smtp_username;
      $mail->Password   = $info->smtp_password;

      if ($info->encryption == 'TLS') {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      }

      $mail->Port       = $info->smtp_port;
    }

    // finally add other informations and send the mail
    try {
      $mail->setFrom($info->from_mail, $info->from_name);
      $mail->addAddress($request->email);

      $mail->isHTML(true);
      $mail->Subject = $mailSubject;
      $mail->Body = $mailBody;

      $mail->send();

      Session::flash('success', 'A verification mail has been sent to your email address.');
    } catch (Exception $e) {
      Session::flash('error', 'Mail could not be sent!');
    }

    return;
  }
  public function signupVerify(Request $request, $token)
  {
    try {
      $customer = Customer::where('verification_token', $token)->firstOrFail();

      // after verify customer email, put "null" in the "verification token"
      $customer->update([
        'email_verified_at' => date('Y-m-d H:i:s'),
        'status' => 1,
        'verification_token' => null
      ]);

      Session::flash('success', 'Your email has verified.');
      // after email verification, authenticate this customer
      Auth::guard('customer')->login($customer);

      return redirect()->route('customer.dashboard');
    } catch (ModelNotFoundException $e) {
      Session::flash('error', 'Could not verify your email!');

      return redirect()->route('customer.signup');
    }
  }

  //login
  public function login(Request $request)
  {
    if ($request->input('redirectPath') == 'event_details') {
      $url = url()->previous();
    }
    if ($request->input('redirectPath') == 'checkout') {
      $url = url()->previous();
    }

    // when user have to redirect to course details page after login.
    if (isset($url)) {
      $request->session()->put('redirectTo', $url);
    }

    return view('frontend.customer.login');
  }


  //authenticate
  public function authentication(Request $request)
  {
    // at first, get the url from session which will be redirect after login
    if ($request->session()->has('redirectTo')) {
      $redirectURL = $request->session()->get('redirectTo');
    } else {
      $redirectURL = null;
    }
    $rules = [
      'username' => 'required',
      'password' => 'required'
    ];

    $info = Basic::select('google_recaptcha_status')->first();
    if ($info->google_recaptcha_status == 1) {
      $rules['g-recaptcha-response'] = 'required|captcha';
    }

    $messages = [];

    if ($info->google_recaptcha_status == 1) {
      $messages['g-recaptcha-response.required'] = 'Please verify that you are not a robot.';
      $messages['g-recaptcha-response.captcha'] = 'Captcha error! try again later or contact site admin.';
    }

    $validator = Validator::make($request->all(), $rules, $messages);


    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    if (
      Auth::guard('customer')->attempt([
        'username' => $request->username,
        'password' => $request->password
      ])
    ) {
      $authAdmin = Auth::guard('customer')->user();

      // check whether the admin's account is active or not
      if ($authAdmin->status == 0) {
        Session::flash('alert', 'Sorry, your account has been deactivated!');
        // logout auth admin as condition not satisfied
        Auth::guard('customer')->logout();

        return redirect()->back();
      } elseif ($authAdmin->email_verified_at == null) {
        Session::flash('alert', 'Sorry, Please verify your email address !');
        // logout auth admin as condition not satisfied
        Auth::guard('customer')->logout();

        return redirect()->back();
      } else {
        // otherwise, redirect auth user to next url
        if ($redirectURL == null) {
          return redirect()->route('customer.dashboard');
        } else {
          // before, redirect to next url forget the session value
          $request->session()->forget('redirectTo');

          return redirect($redirectURL);
        }
      }
    } else {
      return redirect()->back()->with('alert', 'Oops, Username or password does not match!');
    }
  }
  //forget_passord
  public function forget_passord()
  {
    return view('frontend.customer.forget-password');
  }
  //forget_mail
  public function forget_mail(Request $request)
  {
    $rules = [
      'email' => [
        'required',
        'email:rfc,dns',
        new MatchEmailRule('organizer')
      ]
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    $user = Organizer::where('email', $request->email)->first();

    // first, get the mail template information from db
    $mailTemplate = MailTemplate::where('mail_type', 'reset_password')->first();
    $mailSubject = $mailTemplate->mail_subject;
    $mailBody = $mailTemplate->mail_body;

    // second, send a password reset link to user via email
    $info = DB::table('basic_settings')
      ->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
      ->first();

    $name = $user->name;
    $token =  Str::random(32);
    DB::table('password_resets')->insert([
      'email' => $user->email,
      'token' => $token,
    ]);

    $link = '<a href=' . url("customer/reset-password?token=" . $token) . '>Click Here</a>';

    $mailBody = str_replace('{customer_name}', $name, $mailBody);
    $mailBody = str_replace('{password_reset_link}', $link, $mailBody);
    $mailBody = str_replace('{website_title}', $info->website_title, $mailBody);

    // initialize a new mail
    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    // if smtp status == 1, then set some value for PHPMailer
    if ($info->smtp_status == 1) {
      $mail->isSMTP();
      $mail->Host       = $info->smtp_host;
      $mail->SMTPAuth   = true;
      $mail->Username   = $info->smtp_username;
      $mail->Password   = $info->smtp_password;

      if ($info->encryption == 'TLS') {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      }

      $mail->Port       = $info->smtp_port;
    }

    // finally add other informations and send the mail
    try {
      $mail->setFrom($info->from_mail, $info->from_name);
      $mail->addAddress($request->email);

      $mail->isHTML(true);
      $mail->Subject = $mailSubject;
      $mail->Body = $mailBody;

      $mail->send();

      Session::flash('success', 'A mail has been sent to your email address.');
    } catch (\Exception $e) {
      Session::flash('error', 'Mail could not be sent!');
    }

    // store user email in session to use it later
    $request->session()->put('userEmail', $user->email);

    return redirect()->back();
  }
  //reset_password
  public function reset_password()
  {
    return view('frontend.customer.reset-password');
  }
  //update_password
  public function update_password(Request $request)
  {
    $request->validate([
      'password' => 'required|confirmed|min:6',
      'token' => 'required',
    ]);
    $reset = DB::table('password_resets')->where('token', $request->token)->first();
    $email = $reset->email;
    $customer = Customer::where('email',  $email)->first();
    $customer->password = Hash::make($request->password);
    $customer->save();
    Session::flash('success', 'Your password has been reset.');

    return redirect()->route('customer.login');
  }
  public function logout(Request $request)
  {
    Auth::guard('customer')->logout();

    return redirect()->route('customer.login');
  }
  //change_password
  public function change_password()
  {
    return view('frontend.customer.dashboard.change-password');
  }
  //update_password
  public function updated_password(Request $request)
  {
    $rules = [
      'current_password' => [
        'required',
        new MatchOldPasswordRule('customer')

      ],
      'new_password' => 'required|confirmed',
      'new_password_confirmation' => 'required'
    ];

    $messages = [
      'new_password.confirmed' => 'Password confirmation does not match.',
      'new_password_confirmation.required' => 'The confirm new password field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    $customer = Auth::guard('customer')->user();

    $customer->update([
      'password' => Hash::make($request->new_password)
    ]);

    Session::flash('success', 'Password updated successfully!');

    return back();
  }
  //edit_profile
  public function edit_profile()
  {
    return view('frontend.customer.dashboard.edit-profile');
  }
  //update_profile
  public function update_profile(Request $request)
  {
    $request->validate([
      'fname' => 'required',
      'lname' => 'required',
      'email' => [
        'required',
        'email',
        Rule::unique('customers', 'email')->ignore(Auth::guard('customer')->user()->id)
      ],
      'username' => [
        'required',
        'alpha_dash',
        "not_in:$this->admin_user_name",
        Rule::unique('customers', 'username')->ignore(Auth::guard('customer')->user()->id)
      ],
      'photo' => $request->hasFile('photo') ? 'mimes:jpg,jpeg,png' : ''
    ]);

    $in = $request->all();
    $file = $request->file('photo');
    if ($file) {
      $extension = $file->getClientOriginalExtension();
      $directory = public_path('assets/admin/img/customer-profile/');
      $fileName = uniqid() . '.' . $extension;
      @mkdir($directory, 0775, true);
      $file->move($directory, $fileName);
      $in['photo'] = $fileName;
    }
    $id = Auth::guard('customer')->user()->id;
    $update = Customer::find($id)->update($in);
    Session::flash('success', 'Your profile has been successfully updated!');

    return back();
  }
  //wishlist
  public function wishlist()
  {
    $language = $this->getLanguage();
    $customer_id = Auth::guard('customer')->user()->id;
    $wishlist = Wishlist::where('customer_id', $customer_id)
      ->get();
    return view('frontend.customer.dashboard.wishlist', compact('wishlist'));
  }
  //remove_wishlist
  //add_to_wishlist
  public function remove_wishlist($id)
  {
    if (Auth::guard('customer')->check()) {
      $remove = Wishlist::where([['event_id', $id], ['customer_id', Auth::guard('customer')->user()->id]])->first();
      if ($remove) {
        $remove->delete();
        $notification = array('message' => 'Removed from wishlist successfully..!', 'alert-type' => 'info');
      } else {
        $notification = array('message' => 'Something went wrong', 'alert-type' => 'danger');
      }
      return back()->with($notification);
    } else {
      return redirect()->route('customer.login');
    }
  }

  public function facebookRedirect()
  {
    return Socialite::driver('facebook')->redirect();
  }

  public function handleFacebookCallback()
  {
    return $this->authenticationViaProvider('facebook');
  }

  public function googleRedirect()
  {
    return Socialite::driver('google')->redirect();
  }

  public function handleGoogleCallback()
  {
    return $this->authenticationViaProvider('google');
  }

  public function authenticationViaProvider($driver)
  {
    try {

      $user = Socialite::driver($driver)->user();
      $isUser = Customer::where('provider_id', $user->id)->first();

      if ($isUser) {
        Auth::guard('customer')->login($isUser);
        return redirect()->route('customer.dashboard');
      } else {
        //get and insert image
        $avatar = $user->getAvatar();
        $fileContents = file_get_contents($avatar);

        $avatarName = $user->getId() . '.jpg';
        $path = public_path('assets/admin/img/customer-profile/');

        file_put_contents($path . $avatarName, $fileContents);

        $createUser = Customer::create([
          'photo' => $avatarName,
          'fname' => $user->name,
          'email' => $user->email,
          'username' => $user->id,
          'provider' => $driver,
          'provider_id' => $user->id,
          'password' => encrypt('123456'),
          'email_verified_at' => now()
        ]);

        Auth::guard('customer')->login($createUser);
        return redirect()->route('customer.dashboard');
      }
    } catch (Exception $e) {
    }
  }
}
