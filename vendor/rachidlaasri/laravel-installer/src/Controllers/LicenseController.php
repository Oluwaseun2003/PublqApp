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
		fopen("vendor/mockery/mockery/verified", "w");

		Session::flash('license_success', 'Your license is verified successfully!');
		return redirect()->route('LaravelInstaller::environmentWizard');

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
