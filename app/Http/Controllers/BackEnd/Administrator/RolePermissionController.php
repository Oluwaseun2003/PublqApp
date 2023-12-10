<?php

namespace App\Http\Controllers\BackEnd\Administrator;

use App\Http\Controllers\Controller;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RolePermissionController extends Controller
{
  public function index()
  {
    $roles = RolePermission::orderBy('id', 'desc')->get();

    return view('backend.administrator.role-permission.index', compact('roles'));
  }

  public function store(Request $request)
  {
    $rule = ['name' => 'required'];

    $validator = Validator::make($request->all(), $rule);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    RolePermission::create($request->all());

    Session::flash('success', 'New role added successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function permissions($id)
  {
    $role = RolePermission::findOrFail($id);

    return view('backend.administrator.role-permission.permissions', compact('role'));
  }

  public function updatePermissions(Request $request, $id)
  {
    $role = RolePermission::find($id);

    $role->update([
      'permissions' => json_encode($request->permissions)
    ]);

    Session::flash('success', 'Permissions updated successfully!');

    return redirect()->back();
  }

  public function update(Request $request)
  {
    $rule = ['name' => 'required'];

    $validator = Validator::make($request->all(), $rule);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    RolePermission::find($request->id)->update($request->all());

    Session::flash('success', 'Role updated successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    $role = RolePermission::find($id);

    if ($role->adminInfo()->count() > 0) {
      return redirect()->back()->with('warning', 'First delete all the admins of this role!');
    } else {
      $role->delete();

      return redirect()->back()->with('success', 'Role deleted successfully!');
    }
  }
}
