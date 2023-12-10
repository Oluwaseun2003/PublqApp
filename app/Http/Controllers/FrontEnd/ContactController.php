<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\ContactPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class ContactController extends Controller
{
  public function contact()
  {
    $language = $this->getLanguage();

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_contact', 'meta_description_contact')->first();

    $queryResult['pageHeading'] = $this->getPageHeading($language);

    $queryResult['bgImg'] = $this->getBreadcrumb();

    $queryResult['info'] = ContactPage::where('language_id', $language->id)->first();

    return view('frontend.contact', $queryResult);
  }

  public function sendMail(Request $request)
  {

    $info = DB::table('basic_settings')
      ->select('google_recaptcha_status', 'website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name', 'to_mail')
      ->first();

    $rules = [
      'name' => 'required',
      'email' => 'required|email:rfc,dns',
      'subject' => 'required',
      'message' => 'required'
    ];

    if ($info->google_recaptcha_status == 1) {
      $rules['g-recaptcha-response'] = 'required|captcha';
    }

    $msgs = [];

    if ($info->google_recaptcha_status == 1) {
      $msgs['g-recaptcha-response.required'] = 'Please verify that you are not a robot.';
      $msgs['g-recaptcha-response.captcha'] = 'Captcha error! try again later or contact site admin.';
    }

    $validator = Validator::make($request->all(), $rules, $msgs);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $name = $request->name;
    $to = $info->to_mail;
    $subject = $request->subject;

    $message = '<p>Message : ' . $request->message . '</p> <p><strong>Enquirer Name: </strong>' . $name . '<br/><strong>Enquirer Mail: </strong>' . $request->email . '</p>';

    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

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

    try {
      $mail->setFrom($info->from_mail, $info->from_name);
      $mail->addAddress($to);

      $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->Body = $message;

      $mail->send();

      Session::flash('success', 'Mail has been sent.');
    } catch (Exception $e) {
      Session::flash('error', 'Mail could not be sent!');
    }

    return redirect()->back();
  }
}
