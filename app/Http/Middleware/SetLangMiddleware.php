<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLangMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        // if (session()->has('lang')) {
        //     app()->setLocale(session()->get('lang'));
        // } else {
        // $defaultLang = Language::where('is_default', 1)->first();
        // if (!empty($defaultLang)) {
        app()->setLocale('admin');
        // }
        // }

        return $next($request);
    }
}
