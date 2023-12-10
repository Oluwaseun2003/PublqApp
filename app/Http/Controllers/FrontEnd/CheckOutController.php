<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Event\EventContent;
use App\Models\Event\Ticket;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Event\TicketContent;
use App\Models\Language;
use Carbon\Carbon;

class CheckOutController extends Controller
{
  //checkout
  public function checkout2(Request $request)
  {
    $select = false;
    $event_type = Event::where('id', $request->event_id)->select('event_type')->first();
    if ($event_type->event_type == 'venue') {
      foreach ($request->quantity as $qty) {
        if ($qty > 0) {
          $select = true;
          break;
        }
        continue;
      }
    } else {
      if ($request->pricing_type == 'free') {
        $select = true;
      } elseif ($request->pricing_type == 'normal') {
        if ($request->quantity == 0) {
          $select = false;
        } else {
          $select = true;
        }
      } else {
        foreach ($request->quantity as $qty) {
          if ($qty > 0) {
            $select = true;
            break;
          }
          continue;
        }
      }
    }

    if ($select == false) {
      return back()->with(['alert-type' => 'error', 'message' => 'Please Select at least one ticket']);
    }
    if (Auth::guard('customer')->check() == false) {
      return redirect()->route('customer.login', ['redirectPath' => 'event_details']);
    }
    $information = [];
    $information['selTickets'] = '';
    $event = Event::where('id', $request->event_id)->select('event_type')->first();

    $check = false;


    if ($event->event_type == 'online') {
      //**************** stock check start *************** */
      $stock = StockCheck($request->event_id, $request->quantity);
      if ($stock == 'error') {
        $check = true;
      }

      //*************** stock check end **************** */

      if ($request->pricing_type == 'normal') {

        $price = Ticket::where('event_id', $request->event_id)->select('price', 'early_bird_discount', 'early_bird_discount_amount', 'early_bird_discount_type', 'early_bird_discount_date', 'early_bird_discount_time', 'ticket_available', 'ticket_available_type', 'max_ticket_buy_type', 'max_buy_ticket')->first();
        $information['quantity'] = $request->quantity;
        $total = $request->quantity * $price->price;

        //check max buy by customer 

        $max_buy = isTicketPurchaseOnline($request->event_id, $price->max_buy_ticket);
        if ($max_buy['status'] == 'true') {
          $check = true;
        } else {
          $check = false;
        }



        if ($price->early_bird_discount == 'enable') {

          $start = Carbon::parse($price->early_bird_discount_date . $price->early_bird_discount_time);
          $end = Carbon::parse($price->early_bird_discount_date . $price->early_bird_discount_time);
          $today = Carbon::now();
          if ($today <= ($end)) {
            if ($price->early_bird_discount_type == 'fixed') {
              $early_bird_dicount = $price->early_bird_discount_amount;
            } else {
              $early_bird_dicount = ($price->early_bird_discount_amount * $total) / 100;
            }
          } else {
            $early_bird_dicount = 0;
          }
        } else {
          $early_bird_dicount = 0;
        }

        Session::put('total_early_bird_dicount', $early_bird_dicount * $request->quantity);
        $information['total'] = $total;
        Session::put('total', $total);
        Session::put('sub_total', $total);
        Session::put('quantity', $request->quantity);
      } elseif ($request->pricing_type == 'free') {
        $price = Ticket::where('event_id', $request->event_id)->select('max_buy_ticket')->first();
        //check max buy by customer 
        $max_buy = isTicketPurchaseOnline($request->event_id, $price->max_buy_ticket);
        if ($max_buy['status'] == 'true') {
          $check = true;
        }

        $information['quantity'] = $request->quantity;
        $information['total'] = 0;
        Session::put('total', 0);
        Session::put('sub_total', 0);
        Session::put('quantity', $request->quantity);
      }
    } else {
      $tickets = Ticket::where('event_id', $request->event_id)->select('id', 'title', 'pricing_type', 'price', 'variations', 'early_bird_discount', 'early_bird_discount_amount', 'early_bird_discount_type', 'early_bird_discount_date', 'early_bird_discount_time')->get();
      $ticketArr = [];

      foreach ($tickets as $key => $ticket) {

        if ($ticket->pricing_type == 'variation') {
          $varArr1 = json_decode($ticket->variations, true);
          foreach ($varArr1 as $key => $var1) {

            $stock[] = [
              'name' => $var1['name'],
              'price' => $var1['price'],
              'ticket_available' => $var1['ticket_available'] - $request->quantity[$key],
            ];

            //check early_bird discount
            if ($ticket->early_bird_discount == 'enable') {

              $start = Carbon::parse($ticket->early_bird_discount_date . $ticket->early_bird_discount_time);
              $end = Carbon::parse($ticket->early_bird_discount_date . $ticket->early_bird_discount_time);
              $today = Carbon::now();
              if ($today <= ($end)) {
                if ($ticket->early_bird_discount_type == 'fixed') {
                  $early_bird_dicount = $ticket->early_bird_discount_amount;
                } else {
                  $early_bird_dicount = ($var1['price'] * $ticket->early_bird_discount_amount) / 100;
                }
              } else {
                $early_bird_dicount = 0;
              }
            } else {
              $early_bird_dicount = 0;
            }

            $var1['type'] = $ticket->pricing_type;
            $var1['early_bird_dicount'] = $early_bird_dicount;
            $var1['ticket_id'] = $ticket->id;

            $ticketArr[] = $var1;
          }
          Session::put('stock', $stock);
        } elseif ($ticket->pricing_type == 'normal') {

          //check early_bird discount
          if ($ticket->early_bird_discount == 'enable') {

            $start = Carbon::parse($ticket->early_bird_discount_date . $ticket->early_bird_discount_time);
            $end = Carbon::parse($ticket->early_bird_discount_date . $ticket->early_bird_discount_time);
            $today = Carbon::now();
            if ($today <= ($end)) {
              if ($ticket->early_bird_discount_type == 'fixed') {
                $early_bird_dicount = $ticket->early_bird_discount_amount;
              } else {
                $early_bird_dicount = ($ticket->price * $ticket->early_bird_discount_amount) / 100;
              }
            } else {
              $early_bird_dicount = 0;
            }
          } else {
            $early_bird_dicount = 0;
          }

          $language = Language::where('is_default', 1)->first();

          $ticketContent = TicketContent::where([['ticket_id', $ticket->id], ['language_id', $language->id]])->first();
          if (empty($ticketContent)) {
            $ticketContent = TicketContent::where('ticket_id', $ticket->id)->first();
          }

          $ticketArr[] = [
            'ticket_id' => $ticket->id,
            'early_bird_dicount' => $early_bird_dicount,
            'name' => $ticketContent->title,
            'price' => $ticket->price,
            'type' => $ticket->pricing_type
          ];
        } elseif ($ticket->pricing_type == 'free') {
          $language = Language::where('is_default', 1)->first();
          $ticketContent = TicketContent::where([['ticket_id', $ticket->id], ['language_id', $language->id]])->first();
          if (empty($ticketContent)) {
            $ticketContent = TicketContent::where('ticket_id', $ticket->id)->first();
          }

          $ticketArr[] = [
            'ticket_id' => $ticket->id,
            'early_bird_dicount' => 0,
            'name' => $ticketContent->title,
            'price' => 0,
            'type' => $ticket->pricing_type
          ];
        }
      }

      $selTickets = [];
      foreach ($request->quantity as $key => $qty) {
        if ($qty > 0) {
          $selTickets[] = [
            'ticket_id' => $ticketArr[$key]['ticket_id'],
            'early_bird_dicount' => $qty * $ticketArr[$key]['early_bird_dicount'],
            'name' => $ticketArr[$key]['name'],
            'qty' => $qty,
            'price' => $ticketArr[$key]['price'],
          ];
        }
      }

      $sub_total = 0;
      $total_ticket = 0;
      $total_early_bird_dicount = 0;
      foreach ($selTickets as $key => $var) {
        $sub_total += $var['price'] * $var['qty'];
        $total_ticket += $var['qty'];
        $total_early_bird_dicount += $var['early_bird_dicount'];
      }

      $total = $sub_total - $total_early_bird_dicount;

      Session::put('total', round($total, 2));
      Session::put('sub_total', round($sub_total, 2));
      Session::put('quantity', $total_ticket);
      Session::put('selTickets', $selTickets);
      Session::put('discount', NULL);
      Session::put('total_early_bird_dicount', NULL);
      Session::put('total_early_bird_dicount', round($total_early_bird_dicount, 2));

      //stock check 
      foreach ($selTickets as $selTicket) {
        $stock = TicketStockCheck($selTicket['ticket_id'], $selTicket['qty'], $selTicket['name']);

        if ($stock == 'error') {
          $check = true;
          break;
        }

        $check_v = isTicketPurchaseVenueBackend($request->event_id, $selTicket['ticket_id'], $selTicket['name']);
        if ($check_v['status'] == 'true') {
          $check = true;
          break;
        }
      }
    }


    if ($check == true) {
      $notification = array('message' => 'Something went wrong..!', 'alert-type' => 'error');
      return back()->with($notification);
    }

    $event =  EventContent::join('events', 'events.id', 'event_contents.event_id')
      ->where('events.id', $request->event_id)
      ->select('events.*', 'event_contents.title', 'event_contents.slug', 'event_contents.city', 'event_contents.country')
      ->first();
    Session::put('event', $event);
    $online_gateways = OnlineGateway::where('status', 1)->get();
    $offline_gateways = OfflineGateway::where('status', 1)->orderBy('serial_number', 'asc')->get();
    Session::put('online_gateways', $online_gateways);
    Session::put('offline_gateways', $offline_gateways);
    Session::put('event_date', $request->event_date);
    return redirect()->route('check-out');
  }
  public function checkout()
  {
    $information['selTickets'] = Session::get('selTickets');
    $information['total'] = Session::get('total');
    $information['quantity'] = Session::get('quantity');
    $information['total_early_bird_dicount'] = Session::get('total_early_bird_dicount');
    $information['event'] = Session::get('event');
    $information['online_gateways'] = Session::get('online_gateways');
    $information['offline_gateways'] = Session::get('offline_gateways');
    $information['basicData'] = Basic::select('tax')->first();
    $stripe = OnlineGateway::where('keyword', 'stripe')->first();
    $stripe_info = json_decode($stripe->information, true);
    $information['stripe_key'] = $stripe_info['key'];

    return view('frontend.check-out', $information);
  }
}
