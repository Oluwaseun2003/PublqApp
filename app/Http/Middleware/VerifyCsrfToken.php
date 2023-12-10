<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
  /**
   * The URIs that should be excluded from CSRF verification.
   *
   * @var array
   */
  protected $except = [
    '/event-booking/flutterwave/notify',
    '/product-order/flutterwave/notify',
    '/event-booking/razorpay/notify',
    '/event-booking/mercadopago/notify',
    '/event-booking/paytm/notify',
    '/product-order/razorpay/notify',
    '/product-order/mercadopago/notify',
    '/product-order/paytm/notify',
    'organizer/check-qrcode/'
  ];
}
