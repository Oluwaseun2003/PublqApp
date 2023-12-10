<?php

namespace App\Http\Controllers\BackEnd\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\BasicSettings\Basic;
use App\Models\Organizer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Rules\MatchEmailRule;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Event;
use App\Models\Event\Booking;
use App\Models\Language;
use App\Models\OrganizerInfo;
use App\Models\Transaction;
use App\Rules\MatchOldPasswordRule;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OrganizerController extends Controller
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
    $information['total_events'] = Event::where('organizer_id', Auth::guard('organizer')->user()->id)->get()->count();
    $information['total_event_bookings'] = Booking::where('organizer_id', Auth::guard('organizer')->user()->id)->get()->count();
    $information['transcation_count'] = Transaction::where('organizer_id', Auth::guard('organizer')->user()->id)->get()->count();

    //income of event bookings 
    $eventBookingTotalIncomes = DB::table('bookings')
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(price) as total'))
      ->where('paymentStatus', '=', 'completed')
      ->groupBy('month')
      ->whereYear('created_at', '=', date('Y'))
      ->where('organizer_id', Auth::guard('organizer')->user()->id)
      ->get();

    $TotalEventBookings = DB::table('bookings')
      ->select(DB::raw('month(created_at) as month'), DB::raw('count(id) as total'))
      ->where('paymentStatus', '=', 'completed')
      ->groupBy('month')
      ->whereYear('created_at', '=', date('Y'))
      ->where('organizer_id', Auth::guard('organizer')->user()->id)
      ->get();



    $eventMonths = [];

    $eventIncomes = [];
    $totalBookings = [];

    //event icome calculation
    for ($i = 1; $i <= 12; $i++) {
      // get all 12 months name
      $monthNum = $i;
      $dateObj = DateTime::createFromFormat('!m', $monthNum);
      $monthName = $dateObj->format('M');
      array_push($eventMonths, $monthName);

      // get all 12 months's income
      $incomeFound = false;

      foreach ($eventBookingTotalIncomes as $eventIncomeInfo) {
        if ($eventIncomeInfo->month == $i) {
          $incomeFound = true;
          array_push($eventIncomes, $eventIncomeInfo->total);
          break;
        }
      }

      if ($incomeFound == false) {
        array_push($eventIncomes, 0);
      }


      // get all 12 months's c
      $bookingFound = false;

      foreach ($TotalEventBookings as $eventInfo) {
        if ($eventInfo->month == $i) {
          $bookingFound = true;
          array_push($totalBookings, $eventInfo->total);
          break;
        }
      }

      if ($bookingFound == false) {
        array_push($totalBookings, 0);
      }
    }

    $information['eventIncomes'] = $eventIncomes;
    $information['eventMonths'] = $eventMonths;
    $information['totalBookings'] = $totalBookings;

    $information['admin_setting'] = DB::table('basic_settings')->where('uniqid', 12345)->select('organizer_admin_approval', 'admin_approval_notice')->first();


    return view('organizer.index', $information);
  }
  //login
  public function login()
  {
    return view('frontend.organizer.login');
  }
  //signup
  public function signup()
  {
    return view('frontend.organizer.signup');
  }
  //create
  public function create(Request $request)
  {
    $rules = [
      'name' => 'required',
      'username' => [
        'required',
        'alpha_dash',
        'unique:organizers',
        "not_in:$this->admin_user_name"
      ],
      'email' => 'required|email|unique:organizers',
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

    $validator = Validator::make($request->all(), $rules, $messages);


    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }



    $in = $request->all();

    $setting = DB::table('basic_settings')->where('uniqid', 12345)->select('organizer_email_verification', 'organizer_admin_approval')->first();

    if ($setting->organizer_email_verification == 1) {
      // first, get the mail template information from db
      $mailTemplate = MailTemplate::where('mail_type', 'verify_email')->first();

      $mailSubject = $mailTemplate->mail_subject;
      $mailBody = $mailTemplate->mail_body;

      // second, send a password reset link to user via email
      $info = DB::table('basic_settings')
        ->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
        ->first();

      $name = $request->username;
      $token =  $request->email;

      $link = '<a href=' . url("organizers/email/verify?token=" . $token) . '>Click Here</a>';

      $mailBody = str_replace('{username}', $name, $mailBody);
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

        $mail = $mail->send();

        Session::flash('success', ' Verification mail has been sent to your email address!');
      } catch (\Exception $e) {
        Session::flash('error', 'Mail could not be sent!');
        return redirect()->back();
      }

      $in['status'] = 0;
    } else {
      Session::flash('success', 'Sign up successfully completed.Please Login Now');
    }
    if ($setting->organizer_admin_approval == 1) {
      $in['status'] = 0;
    }

    if ($setting->organizer_admin_approval == 0 && $setting->organizer_email_verification == 0) {
      $in['status'] = 1;
    }

    $in['password'] = Hash::make($request->password);
    $organizer = Organizer::create($in);
    $language = $this->getLanguage();
    $in['organizer_id'] = $organizer->id;
    $in['language_id'] = $language->id;
    OrganizerInfo::create($in);

    return redirect()->route('organizer.login');
  }
  //authenticate
  public function authentication(Request $request)
  {
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
      Auth::guard('organizer')->attempt([
        'username' => $request->username,
        'password' => $request->password
      ])
    ) {
      $authAdmin = Auth::guard('organizer')->user();
      $setting = DB::table('basic_settings')->where('uniqid', 12345)->select('organizer_email_verification', 'organizer_admin_approval')->first();

      // check whether the admin's account is active or not
      if ($setting->organizer_email_verification == 1 && $authAdmin->email_verified_at == NULL && $authAdmin->status == 0) {
        Session::flash('alert', 'Please Verify Your Email Address!');

        // logout auth admin as condition not satisfied
        Auth::guard('organizer')->logout();

        return redirect()->back();
      } elseif ($setting->organizer_email_verification == 0 && $setting->organizer_admin_approval == 1) {
        Session::put('secret_login', 0);
        return redirect()->route('organizer.dashboard');
      } else {
        Session::put('secret_login', 0);
        return redirect()->route('organizer.dashboard');
      }
    } else {
      return redirect()->back()->with('alert', 'Oops, Username or password does not match!');
    }
  }
  //forget_passord
  public function forget_passord()
  {
    return view('frontend.organizer.forget-password');
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

    $link = '<a href=' . url("organizer/reset-password?token=" . $token) . '>Click Here</a>';

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
    return view('frontend.organizer.reset-password');
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
    $organizer = Organizer::where('email',  $email)->first();
    $organizer->password = Hash::make($request->password);
    $organizer->save();
    Session::flash('success', 'Your password has been reset.');

    return redirect()->route('organizer.login');
  }
  public function logout(Request $request)
  {
    Auth::guard('organizer')->logout();
    Session::forget('secret_login');

    return redirect()->route('organizer.login');
  }
  //change_password
  public function change_password()
  {
    return view('organizer.change-password');
  }
  //update_password
  public function updated_password(Request $request)
  {
    $rules = [
      'current_password' => [
        'required',
        new MatchOldPasswordRule('organizer')

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
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $organizer = Auth::guard('organizer')->user();

    $organizer->update([
      'password' => Hash::make($request->new_password)
    ]);

    Session::flash('success', 'Password updated successfully!');

    return response()->json(['status' => 'success'], 200);
  }
  //edit_profile
  public function edit_profile()
  {
    $languages = Language::get();
    return view('organizer.edit-profile', compact('languages'));
  }
  //update_profile
  public function update_profile(Request $request)
  {


    $rules = [
      'email' => [
        'required',
        Rule::unique('organizers', 'username')->ignore(Auth::guard('organizer')->user()->id)
      ],
      'username' => [
        'required',
        'alpha_dash',
        "not_in:$this->admin_user_name",
        Rule::unique('organizers', 'username')->ignore(Auth::guard('organizer')->user()->id)
      ],
    ];

    $languages = Language::get();

    $messages = [];

    foreach ($languages as $language) {
      $rules[$language->code . '_name'] = 'required';
      $messages[$language->code . '_name'] = 'The name field is required for ' . $language->name . ' language.';
    }

    if ($request->hasFile('photo')) {
      $rules['photo']  = 'dimensions:width=300,height=300|mimes:jpg,jpeg,png';
    }

    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
      return Response::json(
        [
          'errors' => $validator->getMessageBag()
        ],
        400
      );
    }

    $in = $request->all();
    $organizer = Organizer::find(Auth::guard('organizer')->user()->id);
    $file = $request->file('photo');
    if ($file) {
      $extension = $file->getClientOriginalExtension();
      $directory = public_path('assets/admin/img/organizer-photo/');
      $fileName = uniqid() . '.' . $extension;
      @mkdir($directory, 0775, true);
      $file->move($directory, $fileName);

      @unlink(public_path('assets/admin/img/organizer-photo/') . $organizer->photo);
      $in['photo'] = $fileName;
    }
    $organizer->update($in);

    $languages = Language::get();
    foreach ($languages as $language) {
      $organizer_info = OrganizerInfo::where('organizer_id', $organizer->id)->where('language_id', $language->id)->first();
      if (!$organizer_info) {
        $organizer_info = new OrganizerInfo();
        $organizer_info->language_id = $language->id;
        $organizer_info->organizer_id = $organizer->id;
      }
      $organizer_info->name = $request[$language->code . '_name'];
      $organizer_info->designation = $request[$language->code . '_designation'];
      $organizer_info->country = $request[$language->code . '_country'];
      $organizer_info->city = $request[$language->code . '_city'];
      $organizer_info->state = $request[$language->code . '_state'];
      $organizer_info->zip_code = $request[$language->code . '_zip_code'];
      $organizer_info->address = $request[$language->code . '_address'];
      $organizer_info->details = $request[$language->code . '_details'];
      $organizer_info->save();
    }

    Session::flash('success', 'Updated Successfully');

    return Response::json(['status' => 'success'], 200);
  }
  //verify_email
  public function verify_email()
  {
    return view('organizer.verify');
  }
  //send_link
  public function send_link(Request $request)
  {

    $user = Organizer::where('email', Auth::guard('organizer')->user()->email)->first();


    // first, get the mail template information from db
    $mailTemplate = MailTemplate::where('mail_type', 'verify_email')->first();

    $mailSubject = $mailTemplate->mail_subject;
    $mailBody = $mailTemplate->mail_body;

    // second, send a password reset link to user via email
    $info = DB::table('basic_settings')
      ->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
      ->first();

    $name = $user->name;
    $token =  $user->id;

    $link = '<a href=' . url("organizers/email/verify?token=" . $token) . '>Click Here</a>';

    $mailBody = str_replace('{username}', $user->name, $mailBody);
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
      $mail->addAddress($user->email);

      $mail->isHTML(true);
      $mail->Subject = $mailSubject;
      $mail->Body = $mailBody;

      $mail = $mail->send();

      Session::flash('success', ' Verification mail has been send your email address!');
      return response()->json(['status' => 'success'], 200);
    } catch (\Exception $e) {
      Session::flash('error', 'Mail could not be sent!');
      return redirect()->back();
    }
  }
  //confirm_email'
  public function confirm_email()
  {
    $email = request()->input('token');
    $user = Organizer::where('email', $email)->first();
    $user->email_verified_at = now();
    $setting = DB::table('basic_settings')->where('uniqid', 12345)->select('organizer_admin_approval')->first();
    if ($setting->organizer_admin_approval != 1) {
      $user->status = 1;
    }

    $user->save();
    Auth::guard('organizer')->login($user);
    Session::put('secret_login', 0);
    return redirect()->route('organizer.dashboard');
  }
  //pwa
  public function pwa()
  {
    return view('organizer.pwa.index');
  }
  //check_qrcode
  public function check_qrcode(Request $request)
  {
    $booking_id = $request->booking_id;
    $check = Booking::where('booking_id', $booking_id)->first();
    if ($check) {
      if ($check->scan_status == 1) {
        return response()->json(['alert_type' => 'error', 'message' => 'Already Scanned', 'booking_id' => $check->booking_id]);
      } else {
        if ($check->paymentStatus == 'completed') {
          $check->update([
            'scan_status' => 1
          ]);
          return response()->json(['alert_type' => 'success', 'message' => 'Verified', 'booking_id' => $check->booking_id]);
        } elseif ($check->paymentStatus == 'free') {
          $check->update([
            'scan_status' => 1
          ]);
          return response()->json(['alert_type' => 'success', 'message' => 'Verified', 'booking_id' => $check->booking_id]);
        } elseif ($check->paymentStatus == 'pending') {
          return response()->json(['alert_type' => 'error', 'message' => 'Payment incomplete', 'booking_id' => $check->booking_id]);
        } elseif ($check->paymentStatus == 'rejected') {
          return response()->json(['alert_type' => 'error', 'message' => 'Payment Rejected', 'booking_id' => $check->booking_id]);
        }
      }
    } else {
      return response()->json(['alert_type' => 'error', 'message' => 'Unverified']);
    }
  }

  public function changeTheme(Request $request)
  {
    Session::put('organizer_theme_version', $request->organizer_theme_version);
    return redirect()->back();
  }

  //transaction
  public function transaction(Request $request)
  {
    $transcation_id = null;
    if ($request->filled('transcation_id')) {
      $transcation_id = $request->transcation_id;
    }
    $transcations = Transaction::where('organizer_id', Auth::guard('organizer')->user()->id)
      ->when($transcation_id, function ($query) use ($transcation_id) {
        return $query->where('transcation_id', 'like', '%' . $transcation_id . '%');
      })
      ->orderBy('id', 'desc')->paginate(10);
    return view('organizer.transaction', compact('transcations'));
  }

  //monthly  income
  public function monthly_income(Request $request)
  {
    if ($request->filled('year')) {
      $date = $request->input('year');
    } else {
      $date = date('Y');
    }

    $monthWiseTotalIncomes = DB::table('transactions')->where('organizer_id', Auth::guard('organizer')->user()->id)
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(grand_total) as total'))
      ->where(function ($query) {
        return $query->where('transcation_type', 1)
          ->orWhere('transcation_type', 4);
      })
      ->where('payment_status', 1)
      ->groupBy('month')
      ->whereYear('created_at', '=', $date)
      ->get();


    $monthWiseTotalReject = DB::table('transactions')->where('organizer_id', Auth::guard('organizer')->user()->id)
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(grand_total) as total'))
      ->where('transcation_type', 3)
      ->where('payment_status', 2)
      ->groupBy('month')
      ->whereYear('created_at', '=', $date)
      ->get();


    $monthWiseTotalCommission = DB::table('transactions')->where('organizer_id', Auth::guard('organizer')->user()->id)
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(commission) as total'))
      ->where(function ($query) {
        return $query->where('transcation_type', 1)
          ->orWhere('transcation_type', 3);
      })
      ->where('payment_status', 1)
      ->groupBy('month')
      ->whereYear('created_at', '=', $date)
      ->get();

    $monthWiseTotalExpenses = DB::table('transactions')->where('organizer_id', Auth::guard('organizer')->user()->id)
      ->select(DB::raw('month(created_at) as month'), DB::raw('sum(grand_total) as total'))
      ->where(function ($query) {
        return $query->where('transcation_type', 3)
          ->orWhere('transcation_type', 5);
      })
      ->groupBy('month')
      ->whereYear('created_at', '=', $date)
      ->get();

    $months = [];
    $incomes = [];
    $rejects = [];
    $commissions = [];
    $expenses = [];
    for ($i = 1; $i <= 12; $i++) {
      // get all 12 months name
      $monthNum = $i;
      $dateObj = DateTime::createFromFormat('!m', $monthNum);
      $monthName = $dateObj->format('M');
      array_push($months, $monthName);

      // get all 12 months's income of booking
      $incomeFound = false;
      foreach ($monthWiseTotalIncomes as $incomeInfo) {
        if ($incomeInfo->month == $i) {
          $incomeFound = true;
          array_push($incomes, $incomeInfo->total);
          break;
        }
      }
      if ($incomeFound == false) {
        array_push($incomes, 0);
      }

      // get all 12 months's total reject
      $rejectFound = false;
      foreach ($monthWiseTotalReject as $Reject) {
        if ($Reject->month == $i) {
          $rejectFound = true;
          array_push($rejects, $Reject->total);
          break;
        }
      }
      if ($rejectFound == false) {
        array_push($rejects, 0);
      }

      // get all 12 months's commission of event booking
      $commissionFound = false;
      foreach ($monthWiseTotalCommission as $commissionInfo) {
        if ($commissionInfo->month == $i) {
          $commissionFound = true;
          array_push($commissions, $commissionInfo->total);
          break;
        }
      }
      if ($commissionFound == false) {
        array_push($commissions, 0);
      }

      // get all 12 months's expenses of equipment booking
      $expensesFound = false;
      foreach ($monthWiseTotalExpenses as $expensesInfo) {
        if ($expensesInfo->month == $i) {
          $expensesFound = true;
          array_push($expenses, $expensesInfo->total);
          break;
        }
      }
      if ($expensesFound == false) {
        array_push($expenses, 0);
      }
    }
    $information['months'] = $months;
    $information['incomes'] = $incomes;
    $information['rejects'] = $rejects;
    $information['commissions'] = $commissions;
    $information['expenses'] = $expenses;

    return view('organizer.income', $information);
  }
}
