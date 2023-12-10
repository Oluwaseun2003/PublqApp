<?php

namespace App\Http\Controllers\BackEnd\Organizer;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportTicketStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Conversation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Mews\Purifier\Facades\Purifier;

class SupportTicketController extends Controller
{
    //index
    public function index(Request $request)
    {
        $s_status = SupportTicketStatus::first();
        if ($s_status->support_ticket_status != 'active') {
            return redirect()->route('organizer.dashboard');
        }

        $status = $ticket_id = null;
        if ($request->filled('status')) {
            $status = $request['status'];
        }
        if ($request->filled('ticket_id')) {
            $ticket_id = $request['ticket_id'];
        }



        $collection = SupportTicket::where([['user_id', Auth::guard('organizer')->user()->id], ['user_type', 'organizer']])->when($status, function ($query, $status) {
            return $query->where('status',  $status);
        })
            ->when($ticket_id, function ($query, $ticket_id) {
                return $query->where('id', 'like', '%' . $ticket_id . '%');
            })
            ->orderByDesc('id')
            ->paginate(10);

        return view('organizer.support_ticket.index', compact('collection'));
    }
    //create
    public function create()
    {
        $s_status = SupportTicketStatus::first();
        if ($s_status->support_ticket_status != 'active') {
            return redirect()->route('organizer.dashboard');
        }
        return view('organizer.support_ticket.create');
    }
    //store
    public function store(Request $request)
    {
        $rules = [
            'email' => 'required',
            'subject' => 'required',
        ];

        if ($request->hasFile('attachment')) {
            $rules['attachment'] = 'mimes:zip';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $in = $request->all();
        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $filename = uniqid() . '.' . $attachment->getClientOriginalExtension();
            @mkdir(public_path('assets/admin/img/support-ticket/attachment/'), 0775, true);
            $attachment->move(public_path('assets/admin/img/support-ticket/attachment/'), $filename);
            $in['attachment'] = $filename;
        }
        $in['user_id'] = Auth::guard('organizer')->user()->id;
        $in['user_type'] = 'organizer';
        SupportTicket::create($in);

        Session::flash('success', 'Added Successfully');
        return back();
    }
    //message
    public function message($id)
    {
        $s_status = SupportTicketStatus::first();
        if ($s_status->support_ticket_status != 'active') {
            return redirect()->route('organizer.dashboard');
        }
        $ticket = SupportTicket::where('id', $id)->firstOrFail();
        if ($ticket->user_id != Auth::guard('organizer')->user()->id) {
            return redirect()->route('organizer.dashboard');
        }
        return view('organizer.support_ticket.messages', compact('ticket'));
    }
    public function zip_file_upload(Request $request)
    {
        $file = $request->file('file');
        $allowedExts = array('zip');
        $rules = [
            'file' => [
                function ($attribute, $value, $fail) use ($file, $allowedExts) {
                    $ext = $file->getClientOriginalExtension();
                    if (!in_array($ext, $allowedExts)) {
                        return $fail("Only zip file supported");
                    }
                },
                'max:20000'
            ],
        ];

        $messages = [
            'file.max' => ' zip file may not be greater than 5 MB',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            @mkdir(public_path('assets/front/temp/'), 0775, true);
            $file->move(public_path('assets/front/temp/'), $filename);
            $input['file'] = $filename;
        }

        return response()->json(['data' => 1]);
    }
    public function ticketreply(Request $request, $id)
    {
        $s_status = SupportTicketStatus::first();
        if ($s_status->support_ticket_status != 'active') {
            return redirect()->route('organizer.dashboard');
        }
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
                'max:20000'
            ],
        ];

        $messages = [
            'file.max' => ' zip file may not be greater than 5 MB',
        ];

        $request->validate($rules, $messages);
        $input = $request->all();

        $reply = $request->reply;
        $input['reply'] = Purifier::clean($reply, 'youtube');
        $input['type'] = 3;
        $input['user_id'] = Auth::guard('organizer')->user()->id;

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

        $files = glob('assets/front/temp/*');
        foreach ($files as $file) {
            unlink($file);
        }

        SupportTicket::where('id', $id)->update([
            'last_message' => Carbon::now(),
            'status' => 2,
            'user_id' => Auth::guard('organizer')->user()->id
        ]);

        Session::flash('success', 'Message Sent Successfully');
        return back();
    }

    //delete
    public function delete($id)
    {
        //delete all support ticket
        $support_ticket = SupportTicket::where([['user_id', Auth::guard('organizer')->user()->id], ['user_type', 'organizer'], ['id', $id]])->first();
        if ($support_ticket) {
            //delete conversation 
            $messages = $support_ticket->messages()->get();
            foreach ($messages as $message) {
                @unlink(public_path('assets/admin/img/support-ticket/' . $message->file));
                $message->delete();
            }
            @unlink(public_path('assets/admin/img/support-ticket/attachment/') . $support_ticket->attachment);
            $support_ticket->delete();
        }
        Session::flash('success', 'Deleted Successfully');
        return back();
    }

    public function bulk_delete(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $support_ticket = SupportTicket::where([['user_id', Auth::guard('organizer')->user()->id], ['user_type', 'organizer'], ['id', $id]])->first();
            if ($support_ticket) {
                //delete conversation 
                $messages = $support_ticket->messages()->get();
                foreach ($messages as $message) {
                    @unlink(public_path('assets/admin/img/support-ticket/' . $message->file));
                    $message->delete();
                }
                @unlink(public_path('assets/admin/img/support-ticket/attachment/') . $support_ticket->attachment);
                $support_ticket->delete();
            }
        }
        Session::flash('success', 'Deleted Successfully');
        return Response::json(['status' => 'success'], 200);
    }
}
