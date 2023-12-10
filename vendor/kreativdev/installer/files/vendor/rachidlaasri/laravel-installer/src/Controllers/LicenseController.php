<?php

namespace RachidLaasri\LaravelInstaller\Controllers;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class LicenseController extends Controller
{

    public function __construct()
    {

    }

    /**
     * Display the permissions check page.
     *
     * @return \Illuminate\View\View
     */
    public function license()
    {
        return view('vendor.installer.license');
    }

    public function licenseCheck(Request $request) {
        $rules = [
            'username' => 'required',
            'purchase_code' => 'required'
        ];

        $rules['email'] = 'required';

        $request->validate($rules);

        $itemid = config('installer.item_id');
        $itemname = config('installer.item_name');
        $emailCollectorApi = config('installer.email_api');

        try {
            $requestPurchaseCode = $request->purchase_code; // Get the purchase code from the URL parameter
            
            $headers = [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Bearer d3eCIKWsFeVT1hoMjY7wtZlZMn0tgEO9'
            ];
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.envato.com/v3/market/author/sale?code=' . urlencode($requestPurchaseCode));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            
            $responseBody = curl_exec($ch);
            
            if (curl_errno($ch)) {
                echo 'Error: ' . curl_error($ch);
                exit;
            }
            
            curl_close($ch);
            
            $formattedRes = json_decode($responseBody, true);
            
            $buyerUsername = $formattedRes['buyer'];
            
            if ($request->username != $buyerUsername || $itemid != $formattedRes['item']['id']) {
                Session::flash('license_error', 'Username / Purchase code didn\'t match for this item!');
                return redirect()->back();
            }

            fopen(base_path("vendor/mockery/mockery/verified"), "w");



            // collect Email
            $data = [
                'item_name' => $itemname,
                'email' => $request->email,
                'username' => $request->username,
                'item_id' => $itemid,
                'url' => url('/'),
                'collector_key' => 'rakoombaa',
                'purchase_code' => $request->purchase_code 
            ];
            
            $curl = curl_init();
            
            curl_setopt_array($curl, [
                CURLOPT_URL => $emailCollectorApi,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => http_build_query($data),
                CURLOPT_HTTPHEADER => [
                    'Accept: application/json',
                    'Content-Type: application/x-www-form-urlencoded'
                ],
            ]);
            
            $response = curl_exec($curl);
            
            if (curl_errno($curl)) {
                echo 'Error: ' . curl_error($curl);
            } else {
                echo $response;
            }
            
            curl_close($curl);

            Session::flash('license_success', 'Your license is verified successfully!');
            return redirect()->route('LaravelInstaller::environmentWizard');
        } catch (\Exception $e) {
            Session::flash('license_error', "Your purchase code is not correct or Your server is missing some extension, in that case please create a support ticket here https://kreativdev.freshdesk.com/");
            return redirect()->back();
        }

    }

    public function recurse_copy($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->recurse_copy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    @copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}
