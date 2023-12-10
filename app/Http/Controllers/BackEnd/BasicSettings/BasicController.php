<?php

namespace App\Http\Controllers\BackEnd\BasicSettings;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\CurrencyRequest;
use App\Http\Requests\MailFromAdminRequest;
use App\Models\Timezone;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class BasicController extends Controller
{

  public function information()
  {
    $information['data'] = DB::table('basic_settings')
      ->select('website_title', 'email_address', 'contact_number', 'address', 'latitude', 'longitude', 'timezone')
      ->first();

    $information['timezones'] = Timezone::get();

    return view('backend.basic-settings.contact', $information);
  }

  public function updateInfo(Request $request)
  {
    try {
      $rules = [
        'email_address' => 'required',
        'contact_number' => 'required',
        'address' => 'required',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
      ];

      $validator = Validator::make($request->all(), $rules);

      if ($validator->fails()) {
        return redirect()->back()->withErrors($validator->errors());
      }

      DB::table('basic_settings')->updateOrInsert(
        ['uniqid' => 12345],
        [
          'email_address' => $request->email_address,
          'contact_number' => $request->contact_number,
          'address' => $request->address,
          'latitude' => $request->latitude,
          'longitude' => $request->longitude,
          'timezone' => $request->timezone
        ]
      );
      Session::flash('success', 'Updated Successfully');
      return back();
    } catch (\Exception $th) {
    }
  }


  public function themeAndHome()
  {
    $data = DB::table('basic_settings')->select('theme_version')->first();

    return view('backend.basic-settings.theme-&-home', ['data' => $data]);
  }

  public function updateThemeAndHome(Request $request)
  {
    $rules = [
      'theme_version' => 'required|numeric'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      ['theme_version' => $request->theme_version]
    );

    Session::flash('success', 'Updated Successfully');

    return redirect()->back();
  }


  public function currency()
  {
    $data = DB::table('basic_settings')
      ->select('base_currency_symbol', 'base_currency_symbol_position', 'base_currency_text', 'base_currency_text_position', 'base_currency_rate')
      ->first();

    return view('backend.basic-settings.currency', ['data' => $data]);
  }

  public function updateCurrency(CurrencyRequest $request)
  {
    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'base_currency_symbol' => $request->base_currency_symbol,
        'base_currency_symbol_position' => $request->base_currency_symbol_position,
        'base_currency_text' => $request->base_currency_text,
        'base_currency_text_position' => $request->base_currency_text_position,
        'base_currency_rate' => $request->base_currency_rate
      ]
    );

    Session::flash('success', 'Updated Successfully');

    return redirect()->back();
  }


  public function appearance()
  {
    $data = DB::table('basic_settings')
      ->select('primary_color', 'secondary_color', 'breadcrumb_overlay_color', 'breadcrumb_overlay_opacity')
      ->first();

    return view('backend.basic-settings.appearance', ['data' => $data]);
  }

  public function updateAppearance(Request $request)
  {
    $rules = [
      'primary_color' => 'required',
      'secondary_color' => 'required',
      'breadcrumb_overlay_color' => 'required',
      'breadcrumb_overlay_opacity' => 'required|numeric|min:0|max:1'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'primary_color' => $request->primary_color,
        'secondary_color' => $request->secondary_color,
        'breadcrumb_overlay_color' => $request->breadcrumb_overlay_color,
        'breadcrumb_overlay_opacity' => $request->breadcrumb_overlay_opacity
      ]
    );

    Session::flash('success', 'Updated Successfully');

    return redirect()->back();
  }


  public function mailFromAdmin()
  {
    $data = DB::table('basic_settings')
      ->select('smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
      ->first();

    return view('backend.basic-settings.email.mail-from-admin', ['data' => $data]);
  }

  public function updateMailFromAdmin(MailFromAdminRequest $request)
  {
    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'smtp_status' => $request->smtp_status,
        'smtp_host' => $request->smtp_host,
        'smtp_port' => $request->smtp_port,
        'encryption' => $request->encryption,
        'smtp_username' => $request->smtp_username,
        'smtp_password' => $request->smtp_password,
        'from_mail' => $request->from_mail,
        'from_name' => $request->from_name
      ]
    );

    Session::flash('success', 'Updated Successfully');

    return redirect()->back();
  }

  public function mailToAdmin()
  {
    $data = DB::table('basic_settings')->select('to_mail')->first();

    return view('backend.basic-settings.email.mail-to-admin', ['data' => $data]);
  }

  public function updateMailToAdmin(Request $request)
  {
    $rule = [
      'to_mail' => 'required'
    ];

    $message = [
      'to_mail.required' => 'The mail address field is required.'
    ];

    $validator = Validator::make($request->all(), $rule, $message);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      ['to_mail' => $request->to_mail]
    );

    Session::flash('success', 'Updated Successfully');

    return redirect()->back();
  }


  public function breadcrumb()
  {
    $data = DB::table('basic_settings')->select('breadcrumb')->first();

    return view('backend.basic-settings.breadcrumb', ['data' => $data]);
  }

  public function updateBreadcrumb(Request $request)
  {
    $data = DB::table('basic_settings')->select('breadcrumb')->first();

    $rules = [];

    if (!$request->filled('breadcrumb') && is_null($data->breadcrumb)) {
      $rules['breadcrumb'] = 'required';
    }
    if ($request->hasFile('breadcrumb')) {
      $rules['breadcrumb'] = new ImageMimeTypeRule();
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    if ($request->hasFile('breadcrumb')) {
      $breadcrumbName = UploadFile::update(public_path('assets/admin/img/'), $request->file('breadcrumb'), $data->breadcrumb);

      // finally, store the breadcrumb into db
      DB::table('basic_settings')->updateOrInsert(
        ['uniqid' => 12345],
        ['breadcrumb' => $breadcrumbName]
      );

      Session::flash('success', 'Updated Successfully');
    }

    return redirect()->back();
  }


  public function plugins()
  {
    $data = DB::table('basic_settings')
      ->select('disqus_status', 'disqus_short_name', 'google_recaptcha_status', 'google_recaptcha_site_key', 'google_recaptcha_secret_key', 'whatsapp_status', 'whatsapp_number', 'whatsapp_header_title', 'whatsapp_popup_status', 'whatsapp_popup_message', 'facebook_login_status', 'facebook_app_id', 'facebook_app_secret', 'google_login_status', 'google_client_id', 'google_client_secret')
      ->first();

    return view('backend.basic-settings.plugins', ['data' => $data]);
  }

  public function updateRecaptcha(Request $request)
  {
    $rules = [
      'google_recaptcha_status' => 'required',
      'google_recaptcha_site_key' => 'required',
      'google_recaptcha_secret_key' => 'required'
    ];

    $messages = [
      'google_recaptcha_status.required' => 'The recaptcha status field is required.',
      'google_recaptcha_site_key.required' => 'The recaptcha site key field is required.',
      'google_recaptcha_secret_key.required' => 'The recaptcha secret key field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator);
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'google_recaptcha_status' => $request->google_recaptcha_status,
        'google_recaptcha_site_key' => $request->google_recaptcha_site_key,
        'google_recaptcha_secret_key' => $request->google_recaptcha_secret_key
      ]
    );

    $array = [
      'NOCAPTCHA_SECRET' => $request->google_recaptcha_secret_key,
      'NOCAPTCHA_SITEKEY' => $request->google_recaptcha_site_key
    ];

    setEnvironmentValue($array);
    Artisan::call('config:clear');

    Session::flash('success', 'Updated Successfully');

    return redirect()->back();
  }

  public function updateDisqus(Request $request)
  {
    $rules = [
      'disqus_status' => 'required',
      'disqus_short_name' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator);
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'disqus_status' => $request->disqus_status,
        'disqus_short_name' => $request->disqus_short_name
      ]
    );

    Session::flash('success', 'Updated Successfully');

    return redirect()->back();
  }

  public function updateWhatsApp(Request $request)
  {
    $rules = [
      'whatsapp_status' => 'required',
      'whatsapp_number' => 'required',
      'whatsapp_header_title' => 'required',
      'whatsapp_popup_status' => 'required',
      'whatsapp_popup_message' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator);
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'whatsapp_status' => $request->whatsapp_status,
        'whatsapp_number' => $request->whatsapp_number,
        'whatsapp_header_title' => $request->whatsapp_header_title,
        'whatsapp_popup_status' => $request->whatsapp_popup_status,
        'whatsapp_popup_message' => $request->whatsapp_popup_message
      ]
    );

    Session::flash('success', 'Updated Successfully');

    return redirect()->back();
  }

  public function updateFacebook(Request $request)
  {
    $rules = [
      'facebook_login_status' => 'required',
      'facebook_app_id' => 'required',
      'facebook_app_secret' => 'required'
    ];

    $messages = [
      'facebook_login_status.required' => 'The login status field is required.',
      'facebook_app_id.required' => 'The app id field is required.',
      'facebook_app_secret.required' => 'The app secret field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'facebook_login_status' => $request->facebook_login_status,
        'facebook_app_id' => $request->facebook_app_id,
        'facebook_app_secret' => $request->facebook_app_secret
      ]
    );

    $array = [
      'FACEBOOK_CLIENT_ID' => $request->facebook_app_id,
      'FACEBOOK_CLIENT_SECRET' => $request->facebook_app_secret,
      'FACEBOOK_CALLBACK_URL' => url('login/facebook/callback')
    ];

    setEnvironmentValue($array);
    Artisan::call('config:clear');

    $request->session()->flash('success', 'Updated Successfully');

    return redirect()->back();
  }

  public function updateGoogle(Request $request)
  {
    $rules = [
      'google_login_status' => 'required',
      'google_client_id' => 'required',
      'google_client_secret' => 'required'
    ];

    $messages = [
      'google_login_status.required' => 'The login status field is required.',
      'google_client_id.required' => 'The client id field is required.',
      'google_client_secret.required' => 'The client secret field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'google_login_status' => $request->google_login_status,
        'google_client_id' => $request->google_client_id,
        'google_client_secret' => $request->google_client_secret
      ]
    );

    $array = [
      'GOOGLE_CLIENT_ID' => $request->google_client_id,
      'GOOGLE_CLIENT_SECRET' => $request->google_client_secret,
      'GOOGLE_CALLBACK_URL' => url('login/google/callback')
    ];

    setEnvironmentValue($array);
    Artisan::call('config:clear');

    $request->session()->flash('success', 'Updated Successfully');

    return redirect()->back();
  }


  public function maintenance()
  {
    $data = DB::table('basic_settings')
      ->select('maintenance_img', 'maintenance_status', 'maintenance_msg', 'bypass_token')
      ->first();

    return view('backend.basic-settings.maintenance', ['data' => $data]);
  }

  public function updateMaintenance(Request $request)
  {
    $data = DB::table('basic_settings')->select('maintenance_img')->first();

    $rules = $messages = [];

    if (!$request->filled('maintenance_img') && is_null($data->maintenance_img)) {
      $rules['maintenance_img'] = 'required';

      $messages['maintenance_img.required'] = 'The maintenance image field is required.';
    }
    if ($request->hasFile('maintenance_img')) {
      $rules['maintenance_img'] = new ImageMimeTypeRule();
    }

    $rules['maintenance_status'] = 'required';
    $rules['maintenance_msg'] = 'required';

    $messages['maintenance_msg.required'] = 'The maintenance message field is required.';

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    if ($request->hasFile('maintenance_img')) {
      $imageName = UploadFile::update(public_path('assets/admin/img/'), $request->file('maintenance_img'), $data->maintenance_img);
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'maintenance_img' => $request->hasFile('maintenance_img') ? $imageName : $data->maintenance_img,
        'maintenance_status' => $request->maintenance_status,
        'maintenance_msg' => Purifier::clean($request->maintenance_msg, 'youtube'),
        'bypass_token' => $request->bypass_token
      ]
    );

    if ($request->maintenance_status == 1) {
      $link = route('service_unavailable');

      Artisan::call('down --redirect=' . $link . ' --secret="' . $request->bypass_token . '"');
    } else {
      Artisan::call('up');
    }

    Session::flash('success', 'Updated Successfully');

    return redirect()->back();
  }


  public function footerLogo()
  {
    $data = DB::table('basic_settings')->select('footer_logo')->first();

    return view('backend.basic-settings.footer-logo', ['data' => $data]);
  }

  public function updateFooterLogo(Request $request)
  {
    $data = DB::table('basic_settings')->select('footer_logo')->first();

    $rules = [];

    if (!$request->filled('footer_logo') && is_null($data->footer_logo)) {
      $rules['footer_logo'] = 'required';
    }
    if ($request->hasFile('footer_logo')) {
      $rules['footer_logo'] = new ImageMimeTypeRule();
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    if ($request->hasFile('footer_logo')) {
      $logoName = UploadFile::update(public_path('assets/admin/img/'), $request->file('footer_logo'), $data->footer_logo);

      // finally, store the footer-logo into db
      DB::table('basic_settings')->updateOrInsert(
        ['uniqid' => 12345],
        ['footer_logo' => $logoName]
      );

      Session::flash('success', 'Updated Successfully');
    }

    return redirect()->back();
  }


  public function advertiseSettings()
  {
    $data = DB::table('basic_settings')->select('google_adsense_publisher_id')->first();

    return view('backend.advertisement.settings', ['data' => $data]);
  }

  public function updateAdvertiseSettings(Request $request)
  {
    $rule = [
      'google_adsense_publisher_id' => 'required'
    ];

    $validator = Validator::make($request->all(), $rule);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      ['google_adsense_publisher_id' => $request->google_adsense_publisher_id]
    );

    Session::flash('success', 'Advertise settings updated successfully!');

    return redirect()->back();
  }

  public function pwa(Request $request)
  {
    $pwa = file_get_contents(public_path('manifest.json'));
    $pwa = json_decode($pwa, true);
    $data['pwa'] = $pwa;

    return view('backend.basic-settings.pwa', $data);
  }


  public function updatePwa(Request $request)
  {

    $allowedExts = array('jpg', 'png', 'jpeg');
    $icon128 = $request->file('icon_128');

    $icon256 = $request->file('icon_256');

    $icon512 = $request->file('icon_512');

    $rules = [
      'short_name' => 'required',
      'name' => 'required',
      'theme_color' => 'required',
      'background_color' => 'required',
      'icon_128' => [
        function ($attribute, $value, $fail) use ($icon128, $allowedExts) {
          if (!empty($icon128)) {
            $ext = $icon128->getClientOriginalExtension();
            if (!in_array($ext, $allowedExts)) {
              return $fail("Only png, jpg, jpeg image is allowed");
            }
          }
        },
        'dimensions:width=128,height=128'
      ],
      'icon_256' => [
        function ($attribute, $value, $fail) use ($icon256, $allowedExts) {
          if (!empty($icon256)) {
            $ext = $icon256->getClientOriginalExtension();
            if (!in_array($ext, $allowedExts)) {
              return $fail("Only png, jpg, jpeg image is allowed");
            }
          }
        },
        'dimensions:width=256,height=256'
      ],
      'icon_512' => [
        function ($attribute, $value, $fail) use ($icon512, $allowedExts) {
          if (!empty($icon512)) {
            $ext = $icon512->getClientOriginalExtension();
            if (!in_array($ext, $allowedExts)) {
              return $fail("Only png, jpg, jpeg image is allowed");
            }
          }
        },
        'dimensions:width=512,height=512'
      ]
    ];

    $request->validate($rules);

    $content = $request->except('_token', 'icon_128', 'icon_256', 'icon_512', 'pwa_offline_img', 'start_url');
    $content['start_url'] = './';
    $content['display'] = 'standalone';
    $content['theme_color'] = '#' . $request->theme_color;
    $content['background_color'] = '#' . $request->background_color;


    $preManifest = file_get_contents(public_path('manifest.json'));
    $preManifest = json_decode($preManifest, true);

    if ($request->hasFile('icon_128')) {
      $ext = $icon128->getClientOriginalExtension();
      $filename = uniqid() . '.' . $ext;
      @mkdir(public_path('assets/front/images/'), 0775, true);
      $icon128->move(public_path('assets/front/images/'), $filename);

      $content['icons'][0] = [
        "src" => 'assets/front/images/' . $filename,
        "type" => "image/" . $ext,
        "sizes" => "128X128"
      ];
    } else {
      $content['icons'][0] = [
        "src" => $preManifest['icons'][0]['src'],
        "type" => $preManifest['icons'][0]['type'],
        "sizes" => $preManifest['icons'][0]['sizes']
      ];
    }

    if ($request->hasFile('icon_256')) {
      $ext = $icon256->getClientOriginalExtension();
      $filename = uniqid() . '.' . $ext;
      @mkdir(public_path('assets/front/images/'), 0775, true);
      $icon256->move(public_path('assets/front/images/'), $filename);

      $content['icons'][1] = [
        "src" => 'assets/front/images/' . $filename,
        "type" => "image/" . $ext,
        "sizes" => "256X256"
      ];
    } else {
      $content['icons'][1] = [
        "src" => $preManifest['icons'][1]['src'],
        "type" => $preManifest['icons'][1]['type'],
        "sizes" => $preManifest['icons'][1]['sizes']
      ];
    }

    if ($request->hasFile('icon_512')) {
      $ext = $icon512->getClientOriginalExtension();
      $filename = uniqid() . '.' . $ext;
      @mkdir(public_path('assets/front/images/'), 0775, true);
      $icon512->move(public_path('assets/front/images/'), $filename);

      $content['icons'][2] = [
        "src" => 'assets/front/images/' . $filename,
        "type" => "image/" . $ext,
        "sizes" => "512X512"
      ];
    } else {
      $content['icons'][2] = [
        "src" => $preManifest['icons'][2]['src'],
        "type" => $preManifest['icons'][2]['type'],
        "sizes" => $preManifest['icons'][2]['sizes']
      ];
    }

    $content = json_encode($content);
    file_put_contents(public_path('manifest.json'), $content);

    return back()->with('success', 'Updated Successfully');
  }
  public function pwa_scanner(Request $request)
  {
    $pwa = file_get_contents(public_path('pwa_manifest.json'));
    $pwa = json_decode($pwa, true);
    $data['pwa'] = $pwa;

    return view('backend.basic-settings.pwa_scanner', $data);
  }


  public function updatepwaScanner(Request $request)
  {
    $allowedExts = array('jpg', 'png', 'jpeg');
    $icon128 = $request->file('icon_128');

    $icon256 = $request->file('icon_256');
    $icon512 = $request->file('icon_512');

    $rules = [
      'short_name' => 'required',
      'name' => 'required',
      'theme_color' => 'required',
      'background_color' => 'required',
      'icon_128' => [
        function ($attribute, $value, $fail) use ($icon128, $allowedExts) {
          if (!empty($icon128)) {
            $ext = $icon128->getClientOriginalExtension();
            if (!in_array($ext, $allowedExts)) {
              return $fail("Only png, jpg, jpeg image is allowed");
            }
          }
        },
        'dimensions:width=128,height=128'
      ],
      'icon_256' => [
        function ($attribute, $value, $fail) use ($icon256, $allowedExts) {
          if (!empty($icon256)) {
            $ext = $icon256->getClientOriginalExtension();
            if (!in_array($ext, $allowedExts)) {
              return $fail("Only png, jpg, jpeg image is allowed");
            }
          }
        },
        'dimensions:width=256,height=256'
      ],
      'icon_512' => [
        function ($attribute, $value, $fail) use ($icon512, $allowedExts) {
          if (!empty($icon512)) {
            $ext = $icon512->getClientOriginalExtension();
            if (!in_array($ext, $allowedExts)) {
              return $fail("Only png, jpg, jpeg image is allowed");
            }
          }
        },
        'dimensions:width=512,height=512'
      ]
    ];

    $request->validate($rules);

    $content = $request->except('_token', 'icon_128', 'icon_256', 'icon_512', 'pwa_offline_img', 'start_url');
    $content['start_url'] = './';
    $content['display'] = 'standalone';
    $content['theme_color'] = '#' . $request->theme_color;
    $content['background_color'] = '#' . $request->background_color;

    $preManifest = file_get_contents(public_path('pwa_manifest.json'));
    $preManifest = json_decode($preManifest, true);

    if ($request->hasFile('icon_128')) {
      $ext = $icon128->getClientOriginalExtension();
      $filename = uniqid() . '.' . $ext;
      @mkdir(public_path('assets/pwa_scanner/'), 0775, true);
      $icon128->move(public_path('assets/pwa_scanner/'), $filename);

      $content['icons'][0] = [
        "src" => 'assets/pwa_scanner/' . $filename,
        "type" => "image/" . $ext,
        "sizes" => "128X128"
      ];
    } else {
      $content['icons'][0] = [
        "src" => $preManifest['icons'][0]['src'],
        "type" => $preManifest['icons'][0]['type'],
        "sizes" => $preManifest['icons'][0]['sizes']
      ];
    }

    if ($request->hasFile('icon_256')) {
      $ext = $icon256->getClientOriginalExtension();
      $filename = uniqid() . '.' . $ext;
      @mkdir(public_path('assets/pwa_scanner/'), 0775, true);
      $icon256->move(public_path('assets/pwa_scanner/'), $filename);

      $content['icons'][1] = [
        "src" => 'assets/pwa_scanner/' . $filename,
        "type" => "image/" . $ext,
        "sizes" => "256X256"
      ];
    } else {
      $content['icons'][1] = [
        "src" => $preManifest['icons'][1]['src'],
        "type" => $preManifest['icons'][1]['type'],
        "sizes" => $preManifest['icons'][1]['sizes']
      ];
    }

    if ($request->hasFile('icon_512')) {
      $ext = $icon512->getClientOriginalExtension();
      $filename = uniqid() . '.' . $ext;
      @mkdir(public_path('assets/pwa_scanner/'), 0775, true);
      $icon512->move(public_path('assets/pwa_scanner/'), $filename);

      $content['icons'][2] = [
        "src" => 'assets/pwa_scanner/' . $filename,
        "type" => "image/" . $ext,
        "sizes" => "512X512"
      ];
    } else {
      $content['icons'][2] = [
        "src" => $preManifest['icons'][2]['src'],
        "type" => $preManifest['icons'][2]['type'],
        "sizes" => $preManifest['icons'][2]['sizes']
      ];
    }

    $content = json_encode($content);
    file_put_contents(public_path('pwa_manifest.json'), $content);

    return back()->with('success', 'Updated Successfully');
  }

  // /taxCommission
  public function taxCommission()
  {
    $content = DB::table('basic_settings')->select('tax', 'commission')->first();
    return view('backend.event.tax', compact('content'));
  }

  public function updateEventTaxCommission(Request $request)
  {
    $rules = [
      'tax' => 'required|numeric'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'tax' => $request->tax,
        'commission' => $request->commission
      ]
    );
    $request->session()->flash('success', 'Updated Successfully');

    return redirect()->back();
  }

  //general_settings
  public function general_settings()
  {
    $data = [];

    $data['data'] = DB::table('basic_settings')->first();

    $data['time_zones'] = Timezone::orderBy('country_code', 'asc')->get();

    return view('backend.basic-settings.general-settings', $data);
  }
  //update general settings
  public function update_general_setting(Request $request)
  {
    $data = DB::table('basic_settings')->first();
    $rules = [];

    $rules = [
      'website_title' => 'required',
      'timezone' => 'required',
      'base_currency_symbol' => 'required',
      'base_currency_symbol_position' => 'required',
      'base_currency_text' => 'required',
      'base_currency_text_position' => 'required',
      'base_currency_rate' => 'required|numeric',
      'primary_color' => 'required',
      'breadcrumb_overlay_color' => 'required',
      'breadcrumb_overlay_opacity' => 'required|numeric|min:0|max:1'
    ];



    if (!$request->filled('logo') && is_null($data->logo)) {
      $rules['logo'] = 'required';
    }
    if (!$request->filled('preloader') && is_null($data->preloader)) {
      $rules['preloader'] = 'required';
    }
    if ($request->hasFile('preloader')) {
      $rules['preloader'] = new ImageMimeTypeRule();
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    if ($request->hasFile('logo')) {
      $logoName = UploadFile::update(public_path('assets/admin/img/'), $request->file('logo'), $data->logo);
    } else {
      $logoName = $data->logo;
    }
    if ($request->hasFile('preloader')) {
      $preloaderName = UploadFile::update(public_path('assets/admin/img/'), $request->file('preloader'), $data->preloader);
    } else {
      $preloaderName = $data->preloader;
    }

    if ($request->hasFile('favicon')) {
      $iconName = UploadFile::update(public_path('assets/admin/img/'), $request->file('favicon'), $data->favicon);
    } else {
      $iconName = $data->favicon;
    }

    //update or insert data to basic settigs table 
    DB::table('basic_settings')->updateOrInsert(
      ['uniqid' => 12345],
      [
        'website_title' => $request->website_title,
        'logo' => $logoName,
        'preloader' => $preloaderName,
        'favicon' => $iconName,
        'timezone' => $request->timezone,
        'primary_color' => $request->primary_color,
        'breadcrumb_overlay_color' => $request->breadcrumb_overlay_color,
        'breadcrumb_overlay_opacity' => $request->breadcrumb_overlay_opacity,
        'base_currency_symbol' => $request->base_currency_symbol,
        'base_currency_symbol_position' => $request->base_currency_symbol_position,
        'base_currency_text' => $request->base_currency_text,
        'base_currency_text_position' => $request->base_currency_text_position,
        'base_currency_rate' => $request->base_currency_rate,

      ]
    );

    $array = [
      'APP_TIMEZONE' => $request->timezone,
      'APP_NAME' => str_replace(' ', '_', $request->website_title),
    ];

    setEnvironmentValue($array);

    Session::flash('success', 'Updated Successfully!');

    return redirect()->back();
  }
}
