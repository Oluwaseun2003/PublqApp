<?php

namespace App\Http\Middleware;

use App\Models\BasicSettings\Basic;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Deactive
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
            if ($type == 'organizer') {
                if (Auth::guard('organizer')->user()->status == 0) {
                    if ($request->isMethod('POST') || $request->isMethod('PUT')) {
                        session()->flash('warning', 'Your account is deactive or pending now. Please Contact with admin!');
                        return redirect()->back();
                    }
                }
            } elseif ($type == 'customer') {
                if (Auth::guard('customer')->user()->status == 0) {
                    Auth::guard('customer')->logout();
                    return redirect()->route('customer.login');
                }
            }
        }
        return $next($request);
    }
}
