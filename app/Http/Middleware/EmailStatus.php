<?php

namespace App\Http\Middleware;

use App\Models\BasicSettings\Basic;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EmailStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $type = null)
    {
        if (Session::get('secret_login') != 1) {
            if ($type == 'customer') {
                $userInfo = Auth::guard('customer')->user();
                if ($userInfo->email_verified_at == null) {
                    Auth::guard('customer')->logout();
                    return redirect()->route('customer.dashboard');
                }
            } elseif ($type == 'organizer') {
                $basic = Basic::where('uniqid', 12345)->select('organizer_email_verification')->first();
                if ($basic->organizer_email_verification == 1 && Auth::guard('organizer')->user()->email_verified_at == null) {
                    Session::flash('alert', 'Please verify your email address..!');
                    Auth::guard('organizer')->logout();
                    return redirect()->route('organizer.login');
                }
            }
        }

        return $next($request);
    }
}
