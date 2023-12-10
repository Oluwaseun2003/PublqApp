<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreventRequestsForDeactivatedAccount
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle(Request $request, Closure $next)
  {
    $userInfo = Auth::guard('web')->user();

    if (Auth::check() && $userInfo->status == 0) {
      Auth::guard('web')->logout();

      $request->session()->flash('error', 'Sorry, your account has been deactivated.');

      return redirect()->route('user.login');
    }

    return $next($request);
  }
}
