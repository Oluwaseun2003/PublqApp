<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\SupportTicket;
use App\Models\SupportTicketStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Mews\Purifier\Facades\Purifier;

class SupportTicketController extends Controller
{
  public function index()
  {
    $collection = SupportTicket::where('user_id', Auth::guard('customer')->user()->id)->where('user_type', 'customer')->orderBy('id', 'desc')->get();
    return view('frontend.customer.dashboard.support_ticket.index', compact('collection'));
  }
  //create
  public function create()
  {
    return view('frontend.customer.dashboard.support_ticket.create');
  }
  //store
  public function store(Request $request)
  {
    $request->validate([
      'subject' => 'required',
      'email' => 'required',
      'description' => 'required',
      'attachment' => $request->hasFile('attachment') ? 'mimes:zip|max:5000' : ''
    ]);

    $in = $request->all();
    $in['user_id'] = Auth::guard('customer')->user()->id;
    $in['user_type'] = 'customer';
    $file = $request->file('attachment');
    if ($file) {
      $extension = $file->getClientOriginalExtension();
      $directory = public_path('assets/admin/img/support-ticket/');
      $fileName = uniqid() . '.' . $extension;
      @mkdir($directory, 0775, true);
      $file->move($directory, $fileName);
      $in['attachment'] = $fileName;
    }
    $save = SupportTicket::create($in);
    Session::flash('success', 'Ticket has been submitted successfully..!');
    return back();
  }
  //message
  public function message($id)
  {
    $bex = SupportTicketStatus::first();

    if ($bex->support_ticket_status == 0) {
      return back();
    }
    $ticket = SupportTicket::where('id', $id)->firstOrFail();

    return view('frontend.customer.dashboard.support_ticket.messages', compact('ticket'));
  }
  //reply
  public function reply(Request $request, $id)
  {
    $file = $request->file('file');
    $allowedExts = array('zip');
    $rules = [
      'reply' => 'required',
      'file' => [
        function ($attribute, $value, $fail) use ($file, $allowedExts) {

          $ext = $file->getClientOriginalExtension();
          if (!in_array($ext, $allowedExts)) {
            return $fail("Only zip file supported");
          }
        },
        'max:5000'
      ],
    ];

    $messages = [
      'file.max' => ' zip file may not be greater than 5 MB',
    ];

    $request->validate($rules, $messages);
    $input = $request->all();

    $input['reply'] = Purifier::clean($request->reply, 'youtube');
    $input['type'] = 1;
    $input['user_id'] = Auth::guard('customer')->user()->id;
    $input['support_ticket_id'] = $id;
    if ($request->hasFile('file')) {
      $file = $request->file('file');
      $filename = uniqid() . '.' . $file->getClientOriginalExtension();
      @mkdir(public_path('assets/admin/img/support-ticket/'), 0775, true);
      $file->move(public_path('assets/admin/img/support-ticket/'), $filename);
      $input['file'] = $filename;
    }

    $data = new Conversation();
    $data->create($input);

    SupportTicket::where('id', $id)->update([
      'last_message' => Carbon::now()
    ]);

    Session::flash('success', 'Message Sent Successfully');
    return back();
  }
}
