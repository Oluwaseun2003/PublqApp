<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Customer;
use App\Models\Event\Booking;
use App\Models\Event\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CustomerManagementController extends Controller
{
  private $admin_user_name;
  public function __construct()
  {
    $admin = Admin::select('username')->first();
    $this->admin_user_name = $admin->username;
  }

  public function index(Request $request)
  {
    $searchKey = null;

    if ($request->filled('info')) {
      $searchKey = $request['info'];
    }

    $customers = Customer::when($searchKey, function ($query, $searchKey) {
      return $query->where('fname', 'like', '%' . $searchKey . '%')
        ->orWhere('lname', 'like', '%' . $searchKey . '%')
        ->orWhere('email', 'like', '%' . $searchKey . '%');
    })
      ->orderBy('id', 'desc')
      ->paginate(10);

    return view('backend.end-user.customer.index', compact('customers'));
  }



  public function show(Request $request, $id)
  {

    $bookingId = $paymentStatus = null;
    if ($request->filled('booking_id')) {
      $bookingId = $request['booking_id'];
    }

    if ($request->filled('status')) {
      $paymentStatus = $request['status'];
    }

    $bookings = Booking::when($bookingId, function ($query, $bookingId) {
      return $query->where('booking_id', 'like', '%' . $bookingId . '%');
    })
      ->when($paymentStatus, function ($query, $paymentStatus) {
        return $query->where('paymentStatus', '=', $paymentStatus);
      })
      ->where('customer_id', $id)
      ->orderByDesc('id')
      ->paginate(10);

    $customer = Customer::findOrFail($id);
    $information['bookings'] = $bookings;
    $information['customer'] = $customer;

    return view('backend.end-user.customer.details', $information);
  }

  public function create()
  {
    return view('backend.end-user.customer.create');
  }

  public function store(Request $request)
  {
    $rules = [
      'fname' => 'required',
      'lname' => 'required',
      'email' => [
        'required',
        'email',
        Rule::unique('customers', 'username')
      ],
      'username' => [
        'required',
        'alpha_dash',
        "not_in:$this->admin_user_name",
        Rule::unique('customers', 'username')
      ],
      'password' => 'required|confirmed|min:6'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }


    $in = $request->all();

    $file = $request->file('photo');
    if ($file) {
      $extension = $file->getClientOriginalExtension();
      $directory = public_path('assets/admin/img/customer-profile/');
      $fileName = uniqid() . '.' . $extension;
      @mkdir($directory, 0775, true);
      $file->move($directory, $fileName);
      $in['photo'] = $fileName;
    }
    $in['status'] = 1;
    $in['email_verified_at'] = now();
    $in['password'] = Hash::make($request->password);

    Customer::create($in);
    Session::flash('success', 'Added Successfully');

    return Response::json(['status' => 'success'], 200);
  }

  public function updateAccountStatus(Request $request, $id)
  {

    $user = Customer::find($id);
    if ($request->account_status == 1) {
      $user->update(['status' => 1]);
    } else {
      $user->update(['status' => 0]);
    }
    Session::flash('success', 'Updated Successfully');

    return redirect()->back();
  }
  public function updateEmailStatus(Request $request, $id)
  {
    $user = Customer::find($id);
    if ($request->email_status == 1) {
      $user->update(['email_verified_at' => now()]);
    } else {
      $user->update(['email_verified_at' => null]);
    }
    Session::flash('success', 'Updated Successfully');

    return redirect()->back();
  }
  public function changePassword($id)
  {
    $userInfo = Customer::findOrFail($id);

    return view('backend.end-user.customer.change-password', compact('userInfo'));
  }
  public function updatePassword(Request $request, $id)
  {
    $rules = [
      'new_password' => 'required|confirmed',
      'new_password_confirmation' => 'required'
    ];

    $messages = [
      'new_password.confirmed' => 'Password confirmation does not match.',
      'new_password_confirmation.required' => 'The confirm new password field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $user = Customer::find($id);

    $user->update([
      'password' => Hash::make($request->new_password)
    ]);

    Session::flash('success', 'Updated Successfully');

    return Response::json(['status' => 'success'], 200);
  }

  public function edit($id)
  {
    $customer = Customer::findOrFail($id);
    return view('backend.end-user.customer.edit', compact('customer'));
  }

  //update
  public function update(Request $request, $id, Customer $customer)
  {
    $rules = [
      'fname' => 'required',
      'lname' => 'required',
      'email' => [
        'required',
        'email',
        Rule::unique('customers', 'email')->ignore($id)
      ],
      'username' => [
        'required',
        'alpha_dash',
        "not_in:$this->admin_user_name",
        Rule::unique('customers', 'username')->ignore($id)
      ]

    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }


    $in = $request->all();
    $customer  = Customer::where('id', $id)->first();

    $file = $request->file('photo');
    if ($file) {
      $extension = $file->getClientOriginalExtension();
      $directory = public_path('assets/admin/img/customer-profile/');
      $fileName = uniqid() . '.' . $extension;
      @mkdir($directory, 0775, true);
      $file->move($directory, $fileName);
      $in['photo'] = $fileName;
    }
    @unlink(public_path('assets/admin/img/customer-profile/') . $customer->photo);

    $customer->update($in);
    Session::flash('success', 'Updated Successfully');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    $customer = Customer::find($id);
    $bookings = $customer->bookings()->get();
    foreach ($bookings as $booking) {
      $booking->delete();
    }
    $order_items = $customer->order_items()->get();
    foreach ($order_items as $order_item) {
      $order_item->delete();
    }
    $wishlists = $customer->wishlists()->get();
    foreach ($wishlists as $wishlists) {
      $wishlists->delete();
    }
    $product_reviews = $customer->product_reviews()->get();
    foreach ($product_reviews as $product_review) {
      $product_review->delete();
    }
    $product_reviews = $customer->product_reviews()->get();
    foreach ($product_reviews as $product_review) {
      $product_review->delete();
    }
    $support_tickets = $customer->support_tickets()->get();
    foreach ($support_tickets as $support_ticket) {
      $support_ticket->delete();
    }
    $wishlists = Wishlist::where('customer_id', $customer->id)->get();
    foreach ($wishlists as $key => $wishlist) {
      $wishlist->delete();
    }
    @unlink(public_path('assets/admin/img/customer-profile/') . $customer->photo);
    $customer->delete();

    return redirect()->back()->with('success', 'Deleted Successfully');
  }

  public function bulkDestroy(Request $request)
  {
    $ids = $request->ids;

    foreach ($ids as $id) {
      $customer = Customer::find($id);
      $bookings = $customer->bookings()->get();
      foreach ($bookings as $booking) {
        $booking->delete();
      }
      $order_items = $customer->order_items()->get();
      foreach ($order_items as $order_item) {
        $order_item->delete();
      }
      $product_reviews = $customer->product_reviews()->get();
      foreach ($product_reviews as $product_review) {
        $product_review->delete();
      }

      $support_tickets = $customer->support_tickets()->get();
      foreach ($support_tickets as $support_ticket) {
        $support_ticket->delete();
      }
      $wishlists = Wishlist::where('customer_id', $customer->id)->get();
      foreach ($wishlists as $key => $wishlist) {
        $wishlist->delete();
      }
      @unlink(public_path('assets/admin/img/customer-profile/') . $customer->photo);
      $customer->delete();
    }

    Session::flash('success', 'Deleted Successfully');

    return Response::json(['status' => 'success'], 200);
  }

  //secrtet login
  public function secret_login($id)
  {
    Session::put('secret_login', true);
    $user = Customer::where('id', $id)->first();
    Auth::guard('customer')->login($user);
    return redirect()->route('customer.dashboard');
  }
}
