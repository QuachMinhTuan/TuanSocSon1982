<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; //Phải khai báo thằng này vào đây không sẽ lỗi
use App\User;
class AdminRoleController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            Session(['module_active' => 'role']);
            return $next($request);
        });
    }
    // them quyen
    function addrole()
    {
        $roles = DB::table('roles')->get();
        // return $roles;
        return view('admin.role.addroles', compact('roles'));
    }
    // xu ly them quyen
    function storeaddrole(Request $request)
    {
        if (Auth::user()->email == 'tuanss41@gmail.com') {
            $input = $request->all();
            // return $input;
            $request->validate(
                [
                    'nameRole' => 'required|string|unique:roles',
                ],
                [
                    'required' => ':attribute không được để trống',
                    'unique' => ':attribute phải duy nhất',
                ],
                [
                    'nameRole' => 'Tên quyền',
                ]
            );
            DB::table('roles')->insert([
                'nameRole' => $input['nameRole'],
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]);
            return redirect('admin/role/addrole')->with(
                'status',
                'Đã thêm quyền vào hê thống thành công!'
            );
        } else {
            return redirect('admin/role/addrole')->with(
                'status',
                'Bạn không được phép thêm quyền vào hệ thống!'
            );
        }
    }
    // Them quyen cho user
    function addrolesusers()
    {
        $users = user::all();
        $roles = DB::table('roles')->get();
        $rolesusers = DB::table('rolesusers')->get();
        foreach ($rolesusers as $role) {
            $role->user_id = user::find($role->user_id)->name;
        }
        // return $roles;
        return view(
            'admin.role.addrolesusers',
            compact('rolesusers', 'users', 'roles')
        );
    }
    // Xu ly them quyen cho user
    function addstoreroleuser(Request $request)
    {
        if (Auth::user()->email == 'tuanss41@gmail.com') {
            $input = $request->all();
            // return $input;
            $request->validate(
                [
                    'nameRole' => 'required|string',
                    'nameUser' => 'required|string',
                ],
                [
                    'required' => ':attribute không được để trống',
                    'unique' => ':attribute phải duy nhất',
                ],
                [
                    'nameRole' => 'Tên quyền',
                    'nameUser' => 'Tên user',
                ]
            );
            $roleusers = DB::table('rolesusers')
                ->where('user_id', '=', $input['nameUser'])
                ->where('nameRole', '=', 'admintrator')
                ->get();
            if (count($roleusers) > 0) {
                return redirect('admin/role/addrolesusers')->with(
                    'status',
                    'User này đã có quyền admintrator, bạn không thêm được quyền cho user này nữa!'
                );
            }
            DB::table('rolesusers')->insert([
                'nameRole' => $input['nameRole'],
                'user_id' => $input['nameUser'],
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]);
            return redirect('admin/role/addrolesusers')->with(
                'status',
                'Đã thêm quyền vào hê thống thành công!'
            );
        } else {
            return redirect('admin/role/addrolesusers')->with(
                'status',
                'Bạn không được phép thêm quyền cho bất kỳ user nào trong hệ thống!'
            );
        }
    }
    function deleteroleuser($id)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            $role_user = DB::table('rolesusers')->where('id', $id);
            $user_id=DB::table('rolesusers')->find($id);
            $user=user::find($user_id->user_id);
            $role_user->delete();
            // return $user;
            return redirect('admin/role/addrolesusers')->with(
                'status',
                "Xóa quyền cho user {$user->name} thành công!"
            );
        } else {
            return redirect('admin/role/addrolesusers')->with(
                'status',
                'Bạn không được phép Xóa quyền của user!'
            );
        }
    }
}
