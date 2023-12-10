<?php

namespace App\Http\Controllers\BackEnd\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Http\Requests\Admin\StoreRequest;
use App\Http\Requests\Admin\UpdateRequest;
use App\Models\Admin;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SiteAdminController extends Controller
{
  public function index()
  {
    $information['roles'] = RolePermission::all();

    $information['admins'] = Admin::where('role_id', '!=', NULL)->get();

    return view('backend.administrator.site-admin.index', $information);
  }

  public function store(StoreRequest $request)
  {
    $imageName = UploadFile::store(public_path('assets/admin/img/admins/'), $request->file('image'));

    Admin::create($request->except('image', 'password') + [
      'image' => $imageName,
      'password' => Hash::make($request->password)
    ]);

    $request->session()->flash('success', 'New admin added successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  public function updateStatus(Request $request, $id)
  {
    $admin = Admin::find($id);

    if ($request->status == 1) {
      $admin->update(['status' => 1]);
    } else {
      $admin->update(['status' => 0]);
    }

    $request->session()->flash('success', 'Status updated successfully!');

    return redirect()->back();
  }

  public function update(UpdateRequest $request)
  {
    $admin = Admin::find($request->id);

    if ($request->hasFile('image')) {
      $imageName = UploadFile::update(public_path('assets/admin/img/admins/'), $request->file('image'), $admin->image);
    }

    $admin->update($request->except('image') + [
      'image' => $request->hasFile('image') ? $imageName : $admin->image
    ]);

    $request->session()->flash('success', 'Admin updated successfully!');

    return response()->json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    $admin = Admin::find($id);

    // delete admin profile picture
    @unlink(public_path('assets/admin/img/admins/') . $admin->image);

    $admin->delete();

    return redirect()->back()->with('success', 'Admin deleted successfully!');
  }
}
