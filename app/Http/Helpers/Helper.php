<?php

use App\Models\Advertisement;
use App\Models\BasicSettings\Basic;
use App\Models\Event\Booking;
use App\Models\Event\EventDates;
use App\Models\Event\Ticket;
use App\Models\Organizer;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

if (!function_exists('convertUtf8')) {
  function convertUtf8($value)
  {
    return mb_detect_encoding($value, mb_detect_order(), true) === 'UTF-8' ? $value : mb_convert_encoding($value, 'UTF-8');
  }
}

if (!function_exists('createSlug')) {
  function createSlug($string)
  {
    $slug = preg_replace('/\s+/u', '-', trim($string));
    $slug = str_replace('/', '', $slug);
    $slug = str_replace('?', '', $slug);
    $slug = str_replace(',', '', $slug);

    return mb_strtolower($slug);
  }
}

if (!function_exists('make_slug')) {
  function make_slug($string)
  {
    $slug = preg_replace('/\s+/u', '-', trim($string));
    $slug = str_replace("/", "", $slug);
    $slug = str_replace("?", "", $slug);
    return $slug;
  }
}

if (!function_exists('make_input_name')) {
  function make_input_name($string)
  {
    return preg_replace('/\s+/u', '_', trim($string));
  }
}

if (!function_exists('replaceBaseUrl')) {
  function replaceBaseUrl($html, $type)
  {
    $startDelimiter = 'src=""';
    if ($type == 'summernote') {
      $endDelimiter = '/assets/admin/img/summernote';
    } elseif ($type == 'pagebuilder') {
      $endDelimiter = '/assets/admin/img';
    }

    $startDelimiterLength = strlen($startDelimiter);
    $endDelimiterLength = strlen($endDelimiter);
    $startFrom = $contentStart = $contentEnd = 0;

    while (false !== ($contentStart = strpos($html, $startDelimiter, $startFrom))) {
      $contentStart += $startDelimiterLength;
      $contentEnd = strpos($html, $endDelimiter, $contentStart);

      if (false === $contentEnd) {
        break;
      }

      $html = substr_replace($html, url('/'), $contentStart, $contentEnd - $contentStart);
      $startFrom = $contentEnd + $endDelimiterLength;
    }

    return $html;
  }
}

if (!function_exists('setEnvironmentValue')) {
  function setEnvironmentValue(array $values)
  {
    $envFile = app()->environmentFilePath();
    $str = file_get_contents($envFile);

    if (count($values) > 0) {
      foreach ($values as $envKey => $envValue) {
        $str .= "\n"; // In case the searched variable is in the last line without \n
        $keyPosition = strpos($str, "{$envKey}=");
        $endOfLinePosition = strpos($str, "\n", $keyPosition);
        $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);


        // If key does not exist, add it
        if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
          $str .= "{$envKey}={$envValue}\n";
        } else {
          $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
        }
      }
    }

    $str = substr($str, 0, -1);

    if (!file_put_contents($envFile, $str)) return false;
    return true;
  }
}

if (!function_exists('showAd')) {
  function showAd($resolutionType)
  {
    $ad = Advertisement::where('resolution_type', $resolutionType)->inRandomOrder()->first();
    $adsenseInfo = Basic::query()->select('google_adsense_publisher_id')->first();

    if (!is_null($ad)) {
      if ($resolutionType == 1) {
        $maxWidth = '300px';
        $maxHeight = '250px';
      } else if ($resolutionType == 2) {
        $maxWidth = '300px';
        $maxHeight = '600px';
      } else {
        $maxWidth = '728px';
        $maxHeight = '90px';
      }

      if ($ad->ad_type == 'banner') {
        $markUp = '<a href="' . url($ad->url) . '" target="_blank" onclick="adView(' . $ad->id . ')">
          <img data-src="' . asset('assets/admin/img/advertisements/' . $ad->image) . '" src="' . asset('assets/admin/img/advertisements/' . $ad->image) . '" class="lazy" alt="advertisement" style="width: ' . $maxWidth . ';' . ' ' . 'max-height: ' . $maxHeight . ';max-width: 100%;">
        </a>';

        return $markUp;
      } else {
        $markUp = '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=' . $adsenseInfo->google_adsense_publisher_id . '" crossorigin="anonymous"></script>
        <ins class="adsbygoogle" style="display: block;" data-ad-client="' . $adsenseInfo->google_adsense_publisher_id . '" data-ad-slot="' . $ad->slot . '" data-ad-format="auto" data-full-width-responsive="true"></ins>
        <script>
          (adsbygoogle = window.adsbygoogle || []).push({});
        </script>';

        return $markUp;
      }
    } else {
      return;
    }
  }
}

if (!function_exists('onlyDigitalItemsInCart')) {
  function onlyDigitalItemsInCart()
  {
    $cart = session()->get('cart');
    if (!empty($cart)) {
      foreach ($cart as $key => $cartItem) {
        if ($cartItem['type'] != 'digital') {
          return false;
        }
      }
    }
    return true;
  }
}



if (!function_exists('onlyDigitalItems')) {
  function onlyDigitalItems($order)
  {

    $oitems = $order->orderitems;
    foreach ($oitems as $key => $oitem) {

      if ($oitem->item->type != 'digital') {
        return false;
      }
    }

    return true;
  }
}

if (!function_exists('get_href')) {
  function get_href($data)
  {
    $link_href = '';

    if ($data['type'] == 'home') {
      $link_href = route('index');
    } else if ($data['type'] == 'about') {
      $link_href = route('about');
    } else if ($data['type'] == 'events') {
      $link_href = route('events');
    } else if ($data['type'] == 'organizers') {
      $link_href = route('frontend.all.organizer');
    } else if ($data['type'] == 'shop') {
      $link_href = route('shop');
    } else if ($data['type'] == 'cart') {
      $link_href = route('shopping.cart');
    } else if ($data['type'] == 'shop/checkout') {
      $link_href = route('shop.checkout');
    } else if ($data['type'] == 'blog') {
      $link_href = route('blogs');
    } else if ($data['type'] == 'faq') {
      $link_href = route('faqs');
    } else if ($data['type'] == 'contact') {
      $link_href = route('contact');
    } else if ($data['type'] == 'custom') {
      /**
       * this menu has created using menu-builder from the admin panel.
       * this menu will be used as drop-down or link any outside url to this system.
       */
      if ($data['href'] == '') {
        $link_href = '#';
      } else {
        $link_href = $data['href'];
      }
    } else {
      // this menu is for the custom page which has created from the admin panel.
      $link_href = route('dynamic_page', ['slug' => $data['type']]);
    }

    return $link_href;
  }
}

if (!function_exists('storeTranscation')) {
  function storeTranscation($booking)
  {
    $organizer = Organizer::where('id', $booking->organizer_id)->first();
    if ($organizer != NULL) {
      $pre_balance = $organizer->amount;
      $after_balance = $organizer->amount + ($booking->price - $booking->commission);
    } else {
      $pre_balance = NULL;
      $after_balance = NULL;
    }
    //store data to transcation table 
    $transcation = Transaction::create([
      'transcation_id' => time(),
      'booking_id' => $booking->id,
      'transcation_type' => $booking->transcation_type,
      'customer_id' => Auth::guard('customer')->check() == true ? Auth::guard('customer')->user()->id : null,
      'organizer_id' => $booking->organizer_id,
      'payment_status' => $booking->paymentStatus,
      'payment_method' => $booking->paymentMethod,
      'grand_total' => $booking->price,
      'tax' => $booking->tax,
      'commission' => $booking->commission,
      'pre_balance' => $pre_balance == null ? 0 : $pre_balance,
      'after_balance' => $after_balance,
      'gateway_type' => $booking->gatewayType,
      'currency_symbol' => $booking->currencySymbol,
      'currency_symbol_position' => $booking->currencySymbolPosition,
    ]);
  }
}

if (!function_exists('storeProductTranscation')) {
  function storeProductTranscation($orderInfo)
  {
    //store data to transcation table 
    $transcation = Transaction::create([
      'transcation_id' => time(),
      'booking_id' => $orderInfo->id,
      'transcation_type' => 2,
      'customer_id' => Auth::guard('customer')->check() == true ? Auth::guard('customer')->user()->id : null,
      'organizer_id' => null,
      'payment_status' => $orderInfo->payment_status,
      'payment_method' => $orderInfo->method,
      'grand_total' => $orderInfo->total,
      'tax' => $orderInfo->tax,
      'commission' => null,
      'pre_balance' => null,
      'after_balance' => null,
      'gateway_type' => $orderInfo->gateway_type,
      'currency_symbol' => $orderInfo->currency_symbol,
      'currency_symbol_position' => $orderInfo->currency_symbol_position,
    ]);
  }
}

if (!function_exists('storeOrganizer')) {
  function storeOrganizer($data)
  {
    $organizer = Organizer::where('id', $data['organizer_id'])->first();
    if ($organizer) {
      $organizer->amount = $organizer->amount + ($data['price'] - ($data['commission']));
      $organizer->save();
      return;
    } else {
      return;
    }
  }
}

if (!function_exists('checkWishList')) {
  function checkWishList($event_id, $customer_id)
  {
    $check = App\Models\Event\Wishlist::where('event_id', $event_id)
      ->where('customer_id', $customer_id)
      ->first();
    if ($check) {
      return true;
    } else {
      return false;
    }
  }
}
if (!function_exists('OrganizerEventCount')) {
  function OrganizerEventCount($organizer_id, $admin = null)
  {
    if ($admin == true) {
      $count = App\Models\Event::where('organizer_id', null)
        ->get()->count();
    } else {
      $count = App\Models\Event::where('organizer_id', $organizer_id)
        ->get()->count();
    }

    if ($count) {
      return $count;
    } else {
      return 0;
    }
  }
}
if (!function_exists('categoryWiseEvents')) {
  function categoryWiseEvents($category_id, $language_id, $organizer_id)
  {

    $event_contents = App\Models\Event\EventContent::where('event_category_id', $category_id)
      ->where('language_id', $language_id)->get();

    $eventIds = [];
    foreach ($event_contents as $event) {
      if (!in_array($event->event_id, $eventIds)) {
        array_push($eventIds, $event->event_id);
      }
    }

    $events = App\Models\Event::with([
      'tickets',
      'information' => function ($query) use ($language_id) {
        return $query->where('language_id', $language_id);
      },
    ])
      ->where('organizer_id', $organizer_id)
      ->whereIn('id', $eventIds)
      ->get();

    return $events;
  }
}
if (!function_exists('adminCategoryWiseEvents')) {
  function adminCategoryWiseEvents($category_id, $language_id, $organizer_id)
  {

    $event_contents = App\Models\Event\EventContent::where('event_category_id', $category_id)
      ->where('language_id', $language_id)->get();

    $eventIds = [];
    foreach ($event_contents as $event) {
      if (!in_array($event->event_id, $eventIds)) {
        array_push($eventIds, $event->event_id);
      }
    }

    $events = App\Models\Event::with([
      'tickets',
      'information' => function ($query) use ($language_id) {
        return $query->where('language_id', $language_id);
      },
    ])
      ->where('organizer_id', null)
      ->whereIn('id', $eventIds)
      ->get();

    return $events;
  }
}

if (!function_exists('timeZoneOffset')) {
  function timeZoneOffset($timezone)
  {
    $timezone = App\Models\Timezone::where('timezone', $timezone)->first();
    return $timezone->gmt_offset;
  }
}
if (!function_exists('eventSlug')) {
  function eventSlug($language_id, $event_id)
  {
    $slug = App\Models\Event\EventContent::where('language_id', $language_id)->where('event_id', $event_id)->select('slug')->first();
    if (empty($slug)) {
      $slug = App\Models\Event\EventContent::where('event_id', $event_id)->select('slug')->first();
    }
    return $slug->slug;
  }
}

if (!function_exists('symbolPrice')) {
  function symbolPrice($price)
  {
    $basic = Basic::where('uniqid', 12345)->select('base_currency_symbol_position', 'base_currency_symbol')->first();
    if ($basic->base_currency_symbol_position == 'left') {
      $data = $basic->base_currency_symbol . round($price, 2);
      return str_replace(' ', '', $data);
    } elseif ($basic->base_currency_symbol_position == 'right') {
      $data = round($price, 2) . $basic->base_currency_symbol;
      return str_replace(' ', '', $data);
    }
  }
}


if (!function_exists('DurationCalulate')) {
  function DurationCalulate($start, $end)
  {
    $interval = $end->diff($start);

    $year = $interval->format('%y');
    $month = $interval->format('%m');
    $days = $interval->format('%a');
    $hour = $interval->format('%h');
    $minute = $interval->format('%i');

    $diffent = '';
    if (
      $year != 0
    ) {
      $diffent = $diffent . $year . 'y ';
    }
    if ($month != 0) {
      $diffent = $diffent . $month . 'mo ';
    }
    if ($days != 0) {
      $diffent = $diffent . $days . 'd ';
    }
    if ($hour != 0) {
      $diffent = $diffent . $hour . 'h ';
    }
    if ($minute != 0) {
      $diffent = $diffent . $minute . 'm';
    }
    return $diffent;
  }
}

if (!function_exists('eventDates')) {
  function eventDates($event_id)
  {
    $now = Carbon\Carbon::now()->format('Y-m-d h:i:s');
    $event_dates = EventDates::where('event_id', $event_id)->where('end_date_time', '>=', $now)->orderBy('start_date_time', 'asc')->get();
    return $event_dates;
  }
}

if (!function_exists('eventExpDates')) {
  function eventExpDates($event_id)
  {
    $now = Carbon\Carbon::now()->format('Y-m-d H:i:s');
    $event_dates = EventDates::where('event_id', $event_id)
      ->where('end_date_time', '<', $now)
      ->orderBy('start_date_time', 'desc')
      ->get();
    return $event_dates;
  }
}

if (!function_exists('eventLatestDates')) {
  function eventLatestDates($event_id)
  {
    $now = Carbon\Carbon::now()->format('Y-m-d h:i:s');
    $event_date = EventDates::where('event_id', $event_id)->where('start_date_time', '>=', $now)->orderBy('start_date_time', 'asc')->first();
    if ($event_date) {
      return $event_date;
    } else {
      $event_date = EventDates::where('event_id', $event_id)->orderBy('start_date_time', 'asc')->first();
      return $event_date;
    }
  }
}

if (!function_exists('eventLastEndDates')) {
  function eventLastEndDates($event_id)
  {
    $now = Carbon\Carbon::now()->format('Y-m-d h:i:s');
    $event_date = EventDates::where('event_id', $event_id)->where('end_date_time', '>=', $now)->orderBy('end_date_time', 'desc')->first();
    if ($event_date) {
      return $event_date;
    } else {
      $event_date = EventDates::where('event_id', $event_id)->orderBy('end_date_time', 'asc')->first();
      return $event_date;
    }
  }
}


if (!function_exists('FullDateTime')) {
  function FullDateTime($time)
  {
    $date_time = strtotime($time);
    $date_time = date('D, M d, Y h:ia', $date_time);
    return $date_time;
  }
}
if (!function_exists('FullDateTimeInvoice')) {
  function FullDateTimeInvoice($time)
  {
    $date_time = strtotime($time);
    $date_time = date('d M, Y h:ia', $date_time);
    return $date_time;
  }
}

if (!function_exists('StockCheck')) {
  function stockCheck($event_id, $quantity)
  {
    $ticket = Ticket::where('event_id', $event_id)->select('ticket_available', 'ticket_available_type')->first();
    if ($ticket->ticket_available_type == 'normal') {
      if ($ticket->ticket_available == 0 || $ticket->ticket_available < $quantity) {
        return 'error';
      } else {
        return 'success';
      }
    } else {
      return 'success';
    }
  }
}


if (!function_exists('TicketStockCheck')) {
  function TicketStockCheck($ticket_id, $quantity, $name)
  {
    $ticket = Ticket::where('id', $ticket_id)->first();
    if ($ticket) {
      if ($ticket->pricing_type == 'normal' && $ticket->ticket_available_type == 'limited') {
        if ($ticket->ticket_available == 0 || $ticket->ticket_available < $quantity) {
          $data =  'error';
        } else {
          $data =  'success';
        }
      } elseif ($ticket->pricing_type == 'free' && $ticket->ticket_available_type == 'limited') {
        if ($ticket->ticket_available == 0 || $ticket->ticket_available < $quantity) {
          $data =  'error';
        } else {
          $data =  'success';
        }
      } elseif ($ticket->pricing_type == 'variation') {
        $variations = json_decode($ticket->variations);
        foreach ($variations as $variation) {
          if ($variation->name  == $name) {
            if ($variation->ticket_available_type == 'unlimited') {
              $data =  'success';
            } elseif ($variation->ticket_available == 0 || $variation->ticket_available < $quantity) {
              $data =  'error';
            } else {
              $data =  'success';
            }
          }
        }
      } else {
        $data = 'success';
      }
    } else {
      $data = 'error';
    }
    return $data;
  }
}

if (!function_exists('isTicketPurchaseOnline')) {
  function isTicketPurchaseOnline($event_id, $max_buy_ticket)
  {
    $customer_id = Auth::guard('customer')->user()->id;
    $bookings = Booking::where([['customer_id', $customer_id], ['event_id', $event_id], ['paymentStatus', '!=', 'rejected']])->select('quantity')->get();

    $ticket = Ticket::where('event_id', $event_id)->select('max_ticket_buy_type', 'max_buy_ticket')->first();

    if ($ticket) {
      if ($ticket->max_ticket_buy_type == 'unlimited') {
        $max_buy_ticket = 999999;
      }
    }

    $qty = 0;
    foreach ($bookings as $booking) {
      $qty += $booking->quantity;
    }
    if ($qty >= $max_buy_ticket) {
      return ['status' => 'true', 'p_qty' => $qty];
    } else {
      return ['status' => 'false', 'p_qty' => $qty];
    }
  }
}

if (!function_exists('isTicketPurchaseVenue')) {
  function isTicketPurchaseVenue($event_id, $max_buy_ticket, $ticket_id, $variation_name)
  {
    $ticket = Ticket::where('id', $ticket_id)->first();
    if ($ticket->pricing_type == 'normal' && $ticket->max_ticket_buy_type == 'limited') {
      $max_buy_ticket = $ticket->max_buy_ticket;
    } elseif ($ticket->pricing_type == 'normal' && $ticket->max_ticket_buy_type == 'unlimited') {
      $max_buy_ticket = 999999;
    } elseif ($ticket->pricing_type == 'free' && $ticket->max_ticket_buy_type == 'limited') {
      $max_buy_ticket = $ticket->max_buy_ticket;
    } elseif ($ticket->pricing_type == 'free' && $ticket->max_ticket_buy_type == 'unlimited') {

      $max_buy_ticket = 999999;
    } elseif ($ticket->pricing_type == 'variation') {
      $variations = json_decode($ticket->variations);
      foreach ($variations as $variation) {
        if ($variation->name == $variation_name) {
          if ($variation->max_ticket_buy_type == 'unlimited') {
            $max_buy_ticket = 999999;
          } else {
            $max_buy_ticket = $variation->v_max_ticket_buy;
          }
        }
      }
    } else {
      $max_buy_ticket = 0;
    }

    $customer_id = Auth::guard('customer')->user()->id;
    $bookings = Booking::where([['customer_id', $customer_id], ['event_id', $event_id], ['paymentStatus', '!=', 'rejected']])->get();
    $qty = 0;

    if ($bookings) {
      foreach ($bookings as $booking) {
        $variations = json_decode($booking->variation);
        foreach ($variations as $variation) {
          if ($variation_name == $variation->name && $variation->ticket_id == $ticket_id) {
            $qty += $variation->qty;
          }
        }
      }
      if ($qty > $max_buy_ticket) {
        return ['status' => 'true', 'p_qty' => $qty];
      } else {
        return ['status' => 'false', 'p_qty' => $qty];
      }
    } else {
      return ['status' => 'false', 'p_qty' => $qty];
    }
  }
}


if (!function_exists('isTicketPurchaseVenueBackend')) {
  function isTicketPurchaseVenueBackend($event_id, $ticket_id, $variation_name)
  {
    $ticket = Ticket::where('id', $ticket_id)->first();
    if ($ticket->pricing_type == 'normal' && $ticket->max_ticket_buy_type == 'limited') {
      $max_buy_ticket = $ticket->max_buy_ticket;
    } elseif ($ticket->pricing_type == 'normal' && $ticket->max_ticket_buy_type == 'unlimited') {
      $max_buy_ticket = 999999;
    } elseif ($ticket->pricing_type == 'free' && $ticket->max_ticket_buy_type == 'limited') {
      $max_buy_ticket = $ticket->max_buy_ticket;
    } elseif ($ticket->pricing_type == 'free' && $ticket->max_ticket_buy_type == 'unlimited') {

      $max_buy_ticket = 999999;
    } elseif ($ticket->pricing_type == 'variation') {
      $variations = json_decode($ticket->variations);
      foreach ($variations as $variation) {
        if ($variation->name == $variation_name) {
          if ($variation->max_ticket_buy_type == 'unlimited') {
            $max_buy_ticket = 999999;
          } else {
            $max_buy_ticket = $variation->v_max_ticket_buy;
          }
        }
      }
    } else {
      $max_buy_ticket = 0;
    }

    $customer_id = Auth::guard('customer')->user()->id;
    $bookings = Booking::where([['customer_id', $customer_id], ['event_id', $event_id], ['paymentStatus', '!=', 'rejected']])->get();
    $qty = 0;
    if ($bookings) {
      foreach ($bookings as $booking) {
        $variations = json_decode($booking->variation);
        foreach ($variations as $variation) {
          if ($variation_name == $variation->name && $variation->ticket_id == $ticket_id) {
            $qty += $variation->qty;
          }
        }
      }

      if ($qty > $max_buy_ticket) {
        return ['status' => 'true', 'p_qty' => $qty];
      } else {
        return ['status' => 'false', 'p_qty' => $qty];
      }
    } else {
      return ['status' => 'false', 'p_qty' => $qty];
    }
  }
}
