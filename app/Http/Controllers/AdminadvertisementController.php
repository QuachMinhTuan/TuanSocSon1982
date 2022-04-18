<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; //Phải khai báo thằng này vào đây không sẽ lỗi
use Illuminate\Support\Facades\Auth;

class AdminadvertisementController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            Session(['module_active' => 'advertisement']);
            return $next($request);
        });
    }

    function addadvertisement()
    {
        $banners = DB::table('advertisements')->get();
        return view(
            'admin.advertisements.addadvertisement',
            compact('banners')
        );
    }
    // xu ly them quang cao
    function storeadvertisement(Request $request)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            // xu ly them quang cao
            $input = $request->all();
            // return $input;
            $request->validate(
                [
                    'name' => 'required|unique:advertisements',
                    'file' =>
                        'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    // 'file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ],
                [
                    'name.required' =>
                        ':attribute quảng cáo không được để trống',
                    'required' => ':attribute quảng cáo để upload lên',
                    'unique' =>
                        ':attribute quảng cáo đã tồn tại trong bảng advertisements',
                    'image' => ':attribute có dạng file ảnh',
                    'mimes' => ':attribute có đuôi dạng jpeg,png,jpg,gif,svg',
                    'max' => ':attribute có dung lượng dưới 2048kb',
                ],
                [
                    'name' => 'Tên ',
                    'file' => 'Phải chọn ảnh ',
                ]
            );
            if ($request->hasFile('file')) {
                $file = $request->file; //Gán biến file vào $request:$request->file goi đến cái thuộc tính trong $request
                $fileName = $file->getClientOriginalName();
                // Xu ly trung ten file
                if (!file_exists('public/image/advertisements/' . $fileName)) {
                    $path = $file->move(
                        'public/image/advertisements',
                        $fileName
                    ); //Chuyển file lên server(trong folder public/uploads)
                    $image_banner = 'public/image/advertisements/' . $fileName; //Đường dẫn của file lưu vào database
                } else {
                    $newfileName = time() . '-' . $fileName;
                    $path = $file->move(
                        'public/image/advertisements',
                        $newfileName
                    ); //Chuyển file lên server(trong folder public/uploads)
                    $image_banner =
                        'public/image/advertisements/' . $newfileName; //Đường dẫn của file lưu vào database
                }

                $input['img_banner'] = $image_banner;
            }
            DB::table('advertisements')->insert([
                'name' => $input['name'],
                'img_banner' => $input['img_banner'],
                'creator' => Auth::id(),
                'disabler' => 'active',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]);
            return redirect('admin/advertisement/addadvertisement')->with(
                'status',
                'Đã thêm quảng cáo thành công!'
            );
        } else {
            return redirect('admin/advertisement/addadvertisement')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt lại hay xóa quảng cáo!'
            );
        }
    }
    // edit quang cao(banner)
    function editadvertisement($id)
    {
        // Xu ly quyen user
        // $roles = [];
        // foreach (Auth::user()->roles as $role) {
        //     if ($role->namerole == 'editadvertisement') {
        //         $roles['editadvertisement'] = 'editadvertisement';
        //     }
        //     if ($role->namerole == 'administrators') {
        //         $roles['administrators'] = 'administrators';
        //     }
        // }
        //   return $roles;
        // if (empty($roles)) {
        //     return redirect('admin/advertisement/addadvertisement')->with(
        //         'status',
        //         'Bạn không được phép vào cập nhật quảng cáo!'
        //     );
        // }
        // xu ly edit quang cao
        $banner = DB::table('advertisements')->find($id);
        if ($banner->disabler != 'active') {
            return redirect('admin/advertisement/addadvertisement')->with(
                'status',
                'Bạn không thể cập nhật quảng cáo đang vô hiệu hóa, bạn phải kích hoạt lại hoặc chọn quảng cáo khác đang kích hoạt để cập nhật!'
            );
        } else {
            $banners = DB::table('advertisements')->get();
            return view(
                'admin.advertisements.editadvertisement',
                compact('banner', 'banners')
            );
        }
    }
    // Xu ly cap nhat quang cao
    function updateadvertisement(Request $request, $id)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        if (count($roles) > 0) {
            $input = $request->all();
            // return $input;
            $request->validate(
                [
                    'name' => 'required|unique:advertisements',
                    'file' =>
                        'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    // 'file' => 'required|image',
                ],
                [
                    'name.required' =>
                        ':attribute quảng cáo không được để trống',
                    'required' => ':attribute quảng cáo để upload lên',
                    'unique' =>
                        ':attribute quảng cáo đã tồn tại trong bảng advertisements',
                    'image' => ':attribute có dạng file ảnh',
                    'mimes' => ':attribute có đuôi dạng jpeg,png,jpg,gif,svg',
                    'max' => ':attribute có dung lượng dưới 2048kb',
                ],
                [
                    'name' => 'Tên ',
                    'file' => 'Phải chọn ảnh ',
                ]
            );
            if ($request->hasFile('file')) {
                $file = $request->file; //Gán biến file vào $request:$request->file goi đến cái thuộc tính trong $request
                $fileName = $file->getClientOriginalName();
                // Xu ly trung ten file
                if (!file_exists('public/image/advertisements/' . $fileName)) {
                    $path = $file->move(
                        'public/image/advertisements',
                        $fileName
                    ); //Chuyển file lên server(trong folder public/uploads)
                    $image_banner = 'public/image/advertisements/' . $fileName; //Đường dẫn của file lưu vào database
                } else {
                    $newfileName = time() . '-' . $fileName;
                    $path = $file->move(
                        'public/image/advertisements',
                        $newfileName
                    ); //Chuyển file lên server(trong folder public/uploads)
                    $image_banner =
                        'public/image/advertisements/' . $newfileName; //Đường dẫn của file lưu vào database
                }

                $input['img_banner'] = $image_banner;
            }
            // Xoa anh cu
            $banner = DB::table('advertisements')->find($id);
            if (file_exists($banner->img_banner)) {
                @unlink($banner->img_banner);
            }
            DB::table('advertisements')
                ->where('id', $id)
                ->update([
                    'name' => $input['name'],
                    'img_banner' => $input['img_banner'],
                    'repairer' => Auth::id(),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ]);
            return redirect('admin/advertisement/addadvertisement')->with(
                'status',
                'Đã cập nhật quảng cáo thành công!'
            );
        } else {
            return redirect('admin/advertisement/addadvertisement')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt lại hay xóa quảng cáo!'
            );
        }
    }
    // Disable quang cao
    function disableadvertisement($id)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        if (count($roles) > 0) {
            // xu ly vo hieu hoa quang cao
            $advertisement = DB::table('advertisements')->find($id);
            if ($advertisement->disabler != 'active') {
                return redirect('admin/advertisement/addadvertisement')->with(
                    'status',
                    'Quảng cáo này đã vô hiệu hóa rồi, bạn chỉ chọn quảng cáo đang kích hoạt mới vô hiệu hóa được!'
                );
            } else {
                DB::table('advertisements')
                    ->where('id', $id)
                    ->update([
                        'disabler' => Auth::id(),
                        'updated_at' => date('Y-m-d H:i:s', time()),
                    ]);
                return redirect('admin/advertisement/addadvertisement')->with(
                    'status',
                    'Đã vô hiệu hóa quảng cáo thành công!'
                );
            }
        } else {
            return redirect('admin/advertisement/addadvertisement')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt lại hay xóa quảng cáo!'
            );
        }
    }
    // Kich hoat lai quang cao
    function restoreadvertisement($id)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        if (count($roles) > 0) {
            //  xu ly kich hoat
            $advertisement = DB::table('advertisements')->find($id);
            if ($advertisement->disabler == 'active') {
                return redirect('admin/advertisement/addadvertisement')->with(
                    'status',
                    'Quảng cáo này đã kích hoạt rồi, bạn phải chọn quảng cáo khác đang vô hiệu hóa mới kích hoạt được!'
                );
            } else {
                DB::table('advertisements')
                    ->where('id', $id)
                    ->update([
                        'disabler' => 'active',
                        'updated_at' => date('Y-m-d H:i:s', time()),
                    ]);
                return redirect('admin/advertisement/addadvertisement')->with(
                    'status',
                    'Đã kích hoạt lại quảng cáo thành công!'
                );
            }
        }else{
            return redirect('admin/advertisement/addadvertisement')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt lại hay xóa quảng cáo!'
            );
        }
    }
    // Xoa quang cao
    function deleteadvertisement($id)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        if (count($roles) > 0) {
            $file_banner = DB::table('advertisements')->find($id);
            if (file_exists($file_banner->img_banner)) {
                @unlink($file_banner->img_banner);
            }
            $banner = DB::table('advertisements')->where('id', $id);
            $banner->delete();
            return redirect('admin/advertisement/addadvertisement')->with(
                'status',
                'Đã xóa quảng cáo thành công!'
            );
        } else {
            return redirect('admin/advertisement/addadvertisement')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt lại hay xóa quảng cáo!'
            );
        }
    }
}
