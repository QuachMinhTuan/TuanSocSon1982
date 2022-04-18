<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; //KHai bao thu vien nay de su dung ham ma hoa Hash hon MD5 rat nhieu
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{
    // Phan 24 bai 280 : Active menu nguoi dung truy cap
    // Tao phuong thuc khoi tao construct
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            // Su dung middleware toi uu cho active module_active
            //  Su dung middleware de rang buoc cai session khi di vao moi module, sesion se duoc thay doi theo thiet lap o moi module
            // Neu khong co middleware thi session luon lay cai dau tien la dashbord->khong dung yeu cau bai toan dat ra ->HAY
            Session(['module_active' => 'user']);
            return $next($request);
        });
    }

    //Phan 24 bai 268 : Hien thi danh sach thanh vien
    function list(Request $request)
    {
        // Phan 24 bai 269 : viet chuc nang tim kiem nguoi dung
        // return $request->input('keyword'); //Day la cach lay duoc thong tin tu tren url xuong, phai khai bao request o trong ham

        //    Phan 24 bai 275 : thong ke user theo trang thai : kích hoạt:active va vô hiệu hóa:trash
        $status = request()->input('status');
        // Phan 24 bai 277 : Xoa vinh vien user
        $list_act = [
            'delete' => 'Xóa tạm thời',
        ];
        if ($status == 'trash') {
            $list_act = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xóa vĩnh viễn',
            ];
            // $users=User::all(); //loi vi da su dung thung rac
            // $users=User::withoutTrashed()->paginate(5); //ElequentORM : lay nhung ban ghi ngoai thung rac
            $users = User::onlyTrashed()->paginate(6); //ElequentORM : lay nhung ban ghi trong thung rac
        } else {
            //     // Phan 24 bai 269 : viet chuc nang tim kiem nguoi dung
            $keyword = '';
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }
            $users = User::where('name', 'LIKE', "%{$keyword}%")->paginate(6);
        }
        // $users=User::withTrashed()->where('name','LIKE',"%{$keyword}%")->paginate(10);//xuat ca nhung thang da xoa tam thoi
        //dd($users);//In du lieu print
        //dd($users->total());//In du lieu print
        // Phan 24 bai 268

        $count_user_active = User::count(); //phuong thuc lay so luong cua ORM->HAY
        $count_user_trash = User::onlyTrashed()->count(); //phuong thuc lay so luong cua ORM->HAY
        $count = [$count_user_active, $count_user_trash];

        return view('admin.user.list', compact('users', 'count', 'list_act')); //Gui du lieu sang view de xu ly hien thi,list_act o phan 24 bai 277
    }

    // Phan 24 bai 271 : Them user
    function add()
    {
        return view('admin.user.add');
    }
    // Nơi xu ly submit btn-add(action form) la store
    function store(Request $request)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            if ($request->input('btn-add')) {
                // return $request->input();//xem tat ca
                // return $request->input('name');//xem tat ca
            }
            // Phan 24 bai 272 :validate form
            // Nhanh chong nhat vao auth/Register copy sang va validate
            $request->validate(
                [
                    'name' => 'required|string|max:50',
                    'email' => 'required|string|email|max:50|unique:users',
                    'password' => 'required|min:6',
                    // 'password' => 'required|min:6|confirmed',
                    'password_confirmation' => 'required|same:password',
                ],
                [
                    'required' => ':attribute không được để trống',
                    'min' => ':attribute có độ dài ít nhất :min ký tự',
                    'max' => ':attribute có độ dài tối đa:max ký tự',
                    'unique' => ':attribute đã tồn tại trong bảng users',
                    // 'confirmed'=>':attribute Xác nhận mật khẩu không thành công',
                    'same' => ':attribute phải giống mật khẩu nhập vào',
                ],
                [
                    'name' => 'Tên người dung',
                    'email' => 'Email',
                    'password' => 'Mật khẩu',
                    'password_confirmation' => 'Xác nhận mật khẩu',
                ]
            );
            //    Luu y : Neu de cac quy tac cach nhau boi dau phay theo qui tac dang ky user cua laravel thi quy tac sau ko hoat dong, phai de theo quy tac cach nhau dau |
            // Insert user vao he thong
            User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')), //Ham Hash::make : ma hoa hon rat nhieu ham md5
            ]);
            return redirect('admin/user/list')->with(
                'status',
                'Thêm thành viên thành công!'
            );
        } else {
            return redirect('admin/user/list')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay khôi phục user!'
            );
        }
    }

    // // Phan 24 bai 274 : Xoa ban ghi khoi he thong
    function delete($id)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            if (auth::id() != $id) {
                //Kiem tra xem id cua user dang dang nhap co khac voi id truyen vao hay khong
                $role_user = DB::table('rolesusers')->where('user_id', $id);
                $role_user->delete();
                $user = User::find($id);
                $user->delete(); //phuong thuc xoa cua ELEQUENT ORM
                return redirect('admin/user/list')->with(
                    'status',
                    'Đã xóa tạm thời thành viên thành công!'
                );
            } else {
                return redirect('admin/user/list')->with(
                    'status',
                    'Bạn không thể tự xóa mình ra khỏi hệ thống!'
                );
            }
        } else {
            return redirect('admin/user/list')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay khôi phục user!'
            );
        }
    }

    // // Phan 24 bai 276 : Thuc hien tac vu tren nhieu ban ghi
    function action(Request $request)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            $list_check = $request->input('list_check');
            // <input type="checkbox" name ="list_check[]" value={{$user->id}}>
            // name ="list_check[]" : quy tac dat ten cho checkbox trong php
            // $list_check=$request->all();
            if (isset($list_check)) {
                //Kiem tra $list_check da duoc tao thi
                // return $request->input('list_check');
                // return $request->all();

                if (!empty($list_check)) {
                    $act = $request->input('act');
                    if ($act == 'delete') {
                        foreach ($list_check as $k => $id) {
                            if (Auth::id() == $id) {
                                unset($list_check[$k]);
                            }
                        }
                        // xoa quyen cho cac user truoc
                        $role_user = DB::table('rolesusers')->whereIn('user_id', $list_check);
                        $role_user->delete();
                        User::destroy($list_check);
                        return redirect('admin/user/list')->with(
                            'status',
                            'Bạn đã xóa tạm thời các thành viên đã chọn thành công!'
                        );
                    }
                    if ($act == 'restore') {
                        User::withTrashed()
                            ->whereIn('id', $list_check) //đk:id co thuoc tap hop $list_check hay khong : da hoc cau truc nay trong elequent ORM
                            ->restore();
                        return redirect('admin/user/list')->with(
                            'status',
                            'Bạn đã khôi phục các thành viên đã chọn thành công!'
                        );
                    }
                    // Phan 24 bai 277 : Xoa vinh vien user
                    // Cau truc dieu kien cung tuong tu cua mysql phan WHERE IN)
                    if ($act == 'forceDelete') {
                        User::withTrashed()
                            ->whereIn('id', $list_check) //đk:id co thuoc tap hop $list_check hay khong : da hoc cau truc nay trong elequent ORM
                            ->forceDelete();
                        return redirect('admin/user/list')->with(
                            'status',
                            'Bạn đã xõa vĩnh viễn các user đã chọn thành công!'
                        );
                    }
                }
                return redirect('admin/user/list')->with(
                    'status',
                    'Bạn phải chọn hình thức xóa tạm thời, xóa vĩnh viễn hoặc khôi phục!'
                );
            } else {
                return redirect('admin/user/list')->with(
                    'status',
                    'Bạn cần chọn phần tử cần thực hiện!'
                );
            }
        } else {
            return redirect('admin/user/list')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay khôi phục user!'
            );
        }
    }

    // // Phan 24 bai 278 : Cap nhat thong tin user
    public function edit($id)
    {
        $user = User::find($id);
        return view('admin.user.edit', compact('user'));
    }
    // Phan 24 bai 278 : Cap nhat thong tin user
    public function update(Request $request, $id)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            // if($request->input()){
            // dd($request->all());
            //  return $request->input();//xem tat ca
            //  return $request->all();//xem tat ca
            //  }
            $request->validate(
                [
                    'name' => 'required|string|max:50',
                    'password' => 'required|min:6',
                    'password_confirmation' => 'required|same:password',
                ],
                [
                    'required' => ':attribute không được để trống',
                    'min' => ':attribute có độ dài ít nhất :min ký tự',
                    'max' => ':attribute có độ dài tối đa :max ký tự',
                    'same' => ':attribute phải giống mật khẩu nhập vào',
                ],
                [
                    'name' => 'Tên người dung',
                    'password' => 'Mật khẩu',
                    'password_confirmation' => 'Xác nhận mật khẩu',
                ]
            );
            // Cập nhật lại thông tin user
            User::where('id', $id)->update([
                'name' => $request->input('name'),
                // 'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')), //Ham Hash::make : ma hoa hon rat nhieu ham md5, TOI THIEU 60 KY TU, TRONG DB TA DE HAN 100 CHO YEN TAM
            ]);
            return redirect('admin/user/list')->with(
                'status',
                'Đã cập nhật user thành công'
            );
        } else {
            return redirect('admin/user/list')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay khôi phục user!'
            );
        }
    }
}
