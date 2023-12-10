<?php

namespace App\Http\Controllers\BackEnd\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\StoreRequest;
use App\Http\Requests\Event\UpdateRequest;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Event;
use App\Models\Event\EventImage;
use App\Models\Event\EventContent;
use App\Models\Event\EventDates;
use App\Models\Event\Ticket;
use App\Models\Organizer;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;
use Spatie\GoogleCalendar\Event as GoogleCalendarEvent;



class EventController extends Controller
{
  //index
  public function index(Request $request)
  {
    $information['langs'] = Language::all();

    $language = Language::where('code', $request->language)->firstOrFail();
    $information['language'] = $language;

    $event_type = null;
    if (filled($request->event_type)) {
      $event_type = $request->event_type;
    }
    $title = null;
    if (request()->filled('title')) {
      $title = request()->input('title');
    }

    $events = Event::join('event_contents', 'event_contents.event_id', '=', 'events.id')
      ->join('event_categories', 'event_categories.id', '=', 'event_contents.event_category_id')
      ->where('event_contents.language_id', '=', $language->id)
      ->when($title, function ($query) use ($title) {
        return $query->where('event_contents.title', 'like', '%' . $title . '%');
      })
      ->when($event_type, function ($query) use ($event_type) {
        return $query->where('events.event_type', $event_type);
      })
      ->select('events.*', 'event_contents.id as eventInfoId', 'event_contents.title', 'event_contents.slug', 'event_categories.name as category')
      ->orderByDesc('events.id')
      ->paginate(10);

    $information['events'] = $events;
    return view('backend.event.index', $information);
  }
  //choose_event_type
  public function choose_event_type()
  {
    return view('backend.event.event_type');
  }
  //online_event
  public function add_event()
  {
    $information = [];
    $languages = Language::get();
    $information['languages'] = $languages;
    $countries = Country::get();
    $information['countries'] = $countries;
    $organizers = Organizer::get();
    $information['organizers'] = $organizers;

    $information['getCurrencyInfo']  = $this->getCurrencyInfo();

    return view('backend.event.create', $information);
  }

  public function gallerystore(Request $request)
  {
    $img = $request->file('file');
    $allowedExts = array('jpg', 'png', 'jpeg');
    $rules = [
      'file' => [
        'dimensions:width=1170,height=570',
        function ($attribute, $value, $fail) use ($img, $allowedExts) {
          $ext = $img->getClientOriginalExtension();
          if (!in_array($ext, $allowedExts)) {
            return $fail("Only png, jpg, jpeg images are allowed");
          }
        }
      ]
    ];

    $messages = [
      'file.dimensions' => 'The file has invalid image dimensions ' . $img->getClientOriginalName()
    ];

    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
      $validator->getMessageBag()->add('error', 'true');
      return response()->json($validator->errors());
    }
    $filename = uniqid() . '.jpg';
    @mkdir(public_path('assets/admin/img/event-gallery/'), 0775, true);
    $img->move(public_path('assets/admin/img/event-gallery/'), $filename);
    $pi = new EventImage;
    if (!empty($request->event_id)) {
      $pi->event_id = $request->event_id;
    }
    $pi->image = $filename;
    $pi->save();
    return response()->json(['status' => 'success', 'file_id' => $pi->id]);
  }
  public function imagermv(Request $request)
  {
    $pi = EventImage::where('id', $request->fileid)->first();
    @unlink(public_path('assets/admin/img/event-gallery/') . $pi->image);
    $pi->delete();
    return $pi->id;
  }

  public function store(StoreRequest $request)
  {
    DB::transaction(function () use ($request) {

      //calculate duration 
      if ($request->date_type == 'single') {
        $start = Carbon::parse($request->start_date . $request->start_time);
        $end =  Carbon::parse($request->end_date . $request->end_time);
        $diffent = DurationCalulate($start, $end);
      }
      //calculate duration end

      $in = $request->all();
      $in['duration'] = $request->date_type == 'single' ? $diffent : '';

      $img = $request->file('thumbnail');

      $in['organizer_id'] = $request->organizer_id;
      if ($request->hasFile('thumbnail')) {
        $filename = time() . '.' . $img->getClientOriginalExtension();
        $directory = public_path('assets/admin/img/event/thumbnail/');
        @mkdir($directory, 0775, true);
        $request->file('thumbnail')->move($directory, $filename);
        $in['thumbnail'] = $filename;
      }
      $in['f_price'] = $request->price;
      $in['end_date_time'] = Carbon::parse($request->end_date . ' ' . $request->end_time);
      $event = Event::create($in);

      if ($request->date_type == 'multiple') {
        $i = 1;
        foreach ($request->m_start_date as $key => $date) {
          $start = Carbon::parse($date . $request->m_start_time[$key]);
          $end =  Carbon::parse($request->m_end_date[$key] . $request->m_end_time[$key]);
          $diffent = DurationCalulate($start, $end);

          EventDates::create([
            'event_id' => $event->id,
            'start_date' => $date,
            'start_time' => $request->m_start_time[$key],
            'end_date' => $request->m_end_date[$key],
            'end_time' => $request->m_end_time[$key],
            'duration' => $diffent,
            'start_date_time' => $start,
            'end_date_time' => $end,
          ]);
          if ($i == 1) {
            $event->update([
              'duration' => $diffent
            ]);
          }
          $i++;
        }
        //update event date time
        $event_date = EventDates::where('event_id', $event->id)->orderBy('end_date_time', 'desc')->first();

        $event->end_date_time = $event_date->end_date_time;
        $event->save();
      }


      $in['event_id'] = $event->id;
      if ($request->event_type == 'online') {
        if (!$request->pricing_type) {
          $in['pricing_type'] = 'normal';
        }
        $in['early_bird_discount'] = $request->early_bird_discount_type;
        $in['early_bird_discount_type'] = $request->discount_type;
        Ticket::create($in);
      }

      $slders = $request->slider_images;

      foreach ($slders as $key => $id) {
        $event_image = EventImage::where('id', $id)->first();
        if ($event_image) {
          $event_image->event_id = $event->id;
          $event_image->save();
        }
      }
      $languages = Language::all();

      foreach ($languages as $language) {
        $event_content = new EventContent();
        $event_content->language_id = $language->id;
        $event_content->event_category_id = $request[$language->code . '_category_id'];
        $event_content->event_id = $event->id;
        $event_content->title = $request[$language->code . '_title'];
        if ($request->event_type == 'venue') {
          $event_content->address = $request[$language->code . '_address'];
          $event_content->country = $request[$language->code . '_country'];
          $event_content->state = $request[$language->code . '_state'];
          $event_content->city = $request[$language->code . '_city'];
          $event_content->zip_code = $request[$language->code . '_zip_code'];
        }
        $event_content->slug = createSlug($request[$language->code . '_title']);
        $event_content->description = Purifier::clean($request[$language->code . '_description'], 'youtube');
        $event_content->refund_policy = $request[$language->code . '_refund_policy'];
        $event_content->meta_keywords = $request[$language->code . '_meta_keywords'];
        $event_content->meta_description = $request[$language->code . '_meta_description'];
        $event_content->save();
      }
    });
    Session::flash('success', 'Added Successfully');
    return response()->json(['status' => 'success'], 200);
  }

  /**
   * delete events dates
   */
  public function deleteDate($id)
  {
    $date = EventDates::where('id', $id)->first();
    $date->delete();
    return 'success';
  }
  /**
   * Update status (active/DeActive) of a specified resource.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function updateStatus(Request $request, $id)
  {
    $event = Event::find($id);

    $event->update([
      'status' => $request['status']
    ]);
    Session::flash('success', 'Deleted Successfully');

    return redirect()->back();
  }
  /**
   * Update featured status of a specified resource.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function updateFeatured(Request $request, $id)
  {
    $event = Event::find($id);

    if ($request['is_featured'] == 'yes') {
      $event->is_featured = 'yes';
      $event->save();

      Session::flash('success', 'Updated Successfully');
    } else {
      $event->is_featured = 'no';
      $event->save();

      Session::flash('success', 'Updated Successfully');
    }

    return redirect()->back();
  }

  public function edit($id)
  {
    $event = Event::with('ticket')->findOrFail($id);
    $information['event'] = $event;

    $information['languages'] = Language::all();
    $information['countries'] = Country::get();
    $information['cities'] = City::where('country_id',  $event->country)->orderBy('name', 'asc')->get();
    $information['states'] = State::where('country_id',  $event->country)->orderBy('name', 'asc')->get();
    $organizers = Organizer::get();
    $information['organizers'] = $organizers;

    $information['getCurrencyInfo']  = $this->getCurrencyInfo();

    return view('backend.event.edit', $information);
  }
  public function imagedbrmv(Request $request)
  {
    $pi = EventImage::where('id', $request->fileid)->first();
    $event_id = $pi->event_id;
    $image_count = EventImage::where('event_id', $event_id)->get()->count();
    if ($image_count > 1) {
      @unlink(public_path('assets/admin/img/event-gallery/') . $pi->image);
      $pi->delete();
      return $pi->id;
    } else {
      return 'false';
    }
  }
  public function images($portid)
  {
    $images = EventImage::where('event_id', $portid)->get();
    return $images;
  }

  public function update(UpdateRequest $request)
  {
    //calculate duration 
    if ($request->date_type == 'single') {
      $start = Carbon::parse($request->start_date . $request->start_time);
      $end =  Carbon::parse($request->end_date . $request->end_time);
      $diffent = DurationCalulate($start, $end);
    }
    //calculate duration end
    $img = $request->file('thumbnail');

    $in = $request->all();

    $event = Event::where('id', $request->event_id)->first();
    if ($request->hasFile('thumbnail')) {
      @unlink(public_path('assets/admin/img/event/thumbnail/') . $event->thumbnail);
      $filename = time() . '.' . $img->getClientOriginalExtension();
      @mkdir(public_path('assets/admin/img/event/thumbnail/'), 0775, true);
      $request->file('thumbnail')->move(public_path('assets/admin/img/event/thumbnail/'), $filename);
      $in['thumbnail'] = $filename;
    }

    $languages = Language::all();

    $i = 1;
    foreach ($languages as $language) {
      $event_content = EventContent::where('event_id', $event->id)->where('language_id', $language->id)->first();
      if (!$event_content) {
        $event_content = new EventContent();
      }
      $event_content->language_id = $language->id;
      $event_content->event_category_id = $request[$language->code . '_category_id'];
      $event_content->event_id = $event->id;
      $event_content->title = $request[$language->code . '_title'];
      if ($request->event_type == 'venue') {
        $event_content->address = $request[$language->code . '_address'];
        $event_content->country = $request[$language->code . '_country'];
        $event_content->state = $request[$language->code . '_state'];
        $event_content->city = $request[$language->code . '_city'];
        $event_content->zip_code = $request[$language->code . '_zip_code'];
      }
      $event_content->slug = createSlug($request[$language->code . '_title']);
      $event_content->description = Purifier::clean($request[$language->code . '_description'], 'youtube');
      $event_content->refund_policy = $request[$language->code . '_refund_policy'];
      $event_content->meta_keywords = $request[$language->code . '_meta_keywords'];
      $event_content->meta_description = $request[$language->code . '_meta_description'];
      $event_content->save();
    }
    if ($request->event_type == 'online') {
      if (!$request->pricing_type) {
        $pricing_type = 'normal';
      } else {
        $pricing_type = $request->pricing_type;
      }
      Ticket::where('event_id', $request->event_id)->update([
        'price' => $request->price,
        'f_price' => $request->price,
        'pricing_type' => $pricing_type,
        'ticket_available_type' => $request->ticket_available_type,
        'ticket_available' => $request->ticket_available_type == 'limited' ? $request->ticket_available : null,
        'max_ticket_buy_type' => $request->max_ticket_buy_type,
        'max_buy_ticket' => $request->max_ticket_buy_type == 'limited' ? $request->max_buy_ticket : null,
        'early_bird_discount' => $request->early_bird_discount_type,
        'early_bird_discount_type' => $request->discount_type,
        'early_bird_discount_amount' => $request->early_bird_discount_amount,
        'early_bird_discount_date' => $request->early_bird_discount_date,
        'early_bird_discount_time' => $request->early_bird_discount_time,
      ]);
    }

    $event = Event::where('id', $event->id)->first();

    if ($request->date_type == 'multiple') {
      $i = 1;
      foreach ($request->m_start_date as $key => $date) {
        $start = Carbon::parse($date . $request->m_start_time[$key]);
        $end =  Carbon::parse($request->m_end_date[$key] . $request->m_end_time[$key]);
        $diffent = DurationCalulate($start, $end);

        if (!empty($request->date_ids[$key])) {
          $event_date = EventDates::where('id', $request->date_ids[$key])->first();
          $event_date->start_date = $date;
          $event_date->start_time = $request->m_start_time[$key];
          $event_date->end_date = $request->m_end_date[$key];
          $event_date->end_time = $request->m_end_time[$key];
          $event_date->duration = $diffent;
          $event_date->start_date_time = $start;
          $event_date->end_date_time = $end;
          $event_date->save();
        } else {
          EventDates::create([
            'event_id' => $event->id,
            'start_date' => $date,
            'start_time' => $request->m_start_time[$key],
            'end_date' => $request->m_end_date[$key],
            'end_time' => $request->m_end_time[$key],
            'duration' => $diffent,
            'start_date_time' => $start,
            'end_date_time' => $end,
          ]);
        }
        if ($i == 1) {
          $event->update([
            'duration' => $diffent
          ]);
        }
        $i++;
      }
    }

    if ($request->date_type == 'single') {
      $in['end_date_time'] = Carbon::parse($request->end_date . ' ' . $request->end_time);
      $in['duration'] = $diffent;
    } else {
      //update event date time
      $event_date = EventDates::where('event_id', $event->id)->orderBy('end_date_time', 'desc')->first();

      $in['end_date_time'] = $event_date->end_date_time;
    }


    $event->update($in);
    Session::flash('success', 'Updated Successfully');

    return response()->json(['status' => 'success'], 200);
  }
  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $event = Event::find($id);

    @unlink(public_path('assets/admin/img/event/thumbnail/') . $event->thumbnail);

    $event_contents = EventContent::where('event_id', $event->id)->get();
    foreach ($event_contents as $event_content) {
      $event_content->delete();
    }
    $event_images = EventImage::where('event_id', $event->id)->get();
    foreach ($event_images as $event_image) {
      @unlink(public_path('assets/admin/img/event-gallery/') . $event_image->image);
      $event_image->delete();
    }

    //bookings 
    $bookings = $event->booking()->get();
    foreach ($bookings as $booking) {
      // first, delete the attachment
      @unlink(public_path('assets/admin/file/attachments/') . $booking->attachment);

      // second, delete the invoice
      @unlink(public_path('assets/admin/file/invoices/') . $booking->invoice);

      $booking->delete();
    }

    //tickets
    $tickets = $event->tickets()->get();
    foreach ($tickets as $ticket) {
      $ticket->delete();
    }
    //wishlists
    $wishlists = $event->wishlists()->get();
    foreach ($wishlists as $wishlist) {
      $wishlist->delete();
    }

    //dates
    $dates = $event->dates()->get();
    foreach ($dates as $date) {
      $date->delete();
    }

    // finally delete the event
    $event->delete();

    return redirect()->back()->with('success', 'Deleted Successfully');
  }
  //bulk_delete
  public function bulk_delete(Request $request)
  {
    foreach ($request->ids as $id) {
      $event = Event::find($id);

      @unlink(public_path('assets/admin/img/event/thumbnail/') . $event->thumbnail);

      $event_contents = EventContent::where('event_id', $event->id)->get();
      foreach ($event_contents as $event_content) {
        $event_content->delete();
      }
      $event_images = EventImage::where('event_id', $event->id)->get();
      foreach ($event_images as $event_image) {
        @unlink(public_path('assets/admin/img/event-gallery/') . $event_image->image);
        $event_image->delete();
      }

      //bookings 
      $bookings = $event->booking()->get();
      foreach ($bookings as $booking) {
        // first, delete the attachment
        @unlink(public_path('assets/admin/file/attachments/') . $booking->attachment);

        // second, delete the invoice
        @unlink(public_path('assets/admin/file/invoices/') . $booking->invoice);

        $booking->delete();
      }

      //tickets
      $tickets = $event->tickets()->get();
      foreach ($tickets as $ticket) {
        $ticket->delete();
      }

      //wishlists
      $wishlists = $event->wishlists()->get();
      foreach ($wishlists as $wishlist) {
        $wishlist->delete();
      }

      //dates
      $dates = $event->dates()->get();
      foreach ($dates as $date) {
        $date->delete();
      }

      // finally delete the event
      $event->delete();
    }
    Session::flash('success', 'Deleted Successfully');
    return response()->json(['status' => 'success'], 200);
  }
}
