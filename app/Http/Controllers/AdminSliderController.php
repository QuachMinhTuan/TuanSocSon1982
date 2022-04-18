<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; //Phải khai báo thằng này vào đây không sẽ lỗi

class AdminSliderController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            Session(['module_active' => 'slider']);
            return $next($request);
        });
    }
    // Add silder
    function addslider()
    {
        $sliders = DB::table('sliders')->get();
        return view('admin.slider.addslider', compact('sliders'));
    }
    // Xu ly validate them sliceder
    function addstoreslider(Request $request)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            // xu ly them slider
            $input = $request->all();
            // return $input;
            $request->validate(
                [
                    'name_slider' => 'required|unique:sliders',
                    'file' =>
                        'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    // 'file' => 'required|image',
                ],
                [
                    'name_slider.required' =>
                        ':attribute slider không được để trống',
                    'required' => ':attribute slider',
                    'unique' =>
                        ':attribute slider đã tồn tại trong bảng sliders',
                    'image' => ':attribute có dạng file ảnh',
                    'mimes' => ':attribute có đuôi dạng jpeg,png,jpg,gif,svg',
                    'max' => ':attribute có dung lượng dưới 2048kb',
                ],
                [
                    'name_slider' => 'Tên ',
                    'file' => 'Phải chọn ảnh',
                ]
            );
            if ($request->hasFile('file')) {
                //    echo "Có file"."<br>";
                $file = $request->file; //Gán biến file vào $request:$request->file goi đến cái thuộc tính trong $request
                $fileName = $file->getClientOriginalName();
                //   echo $file->getClientOriginalName();
                //   echo "<br>";
                //   Lay ten file khong co duoi
                // echo pathinfo($fileName, PATHINFO_FILENAME)."<br>";
                //   echo 'public/products/'.$file->getClientOriginalName();

                // Lấy đuôi file
                // echo  "Duoi file : ".$file->getClientOriginalExtension()."<br>";

                // Xu ly trung ten file
                if (!file_exists('public/image/sliders/' . $fileName)) {
                    $path = $file->move(
                        'public/image/sliders',
                        $file->getClientOriginalName()
                    ); //Chuyển file lên server(trong folder public/uploads)
                    $image_slider = 'public/image/sliders/' . $fileName; //Đường dẫn của file lưu vào database
                } else {
                    $newfileName = time() . '-' . $fileName;
                    $path = $file->move('public/image/sliders', $newfileName); //Chuyển file lên server(trong folder public/uploads)
                    $image_slider = 'public/image/sliders/' . $newfileName; //Đường dẫn của file lưu vào database
                }
                // echo $path;
                //  $thumbnail='public/products/'.$fileName; //Đường dẫn của file lưu vào database
                //  echo $thumbnail;
                $input['image_slider'] = $image_slider;
                //  echo $input['thumbnail'];
            }
            DB::table('sliders')->insert([
                'name_slider' => $input['name_slider'],
                'image_slider' => $input['image_slider'],
                'creator' => Auth::id(),
                'disabler' => 'active',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]);
            return redirect('admin/slider/addslider')->with(
                'status',
                'Đã thêm slider sản phẩm thành công!'
            );
        } else {
            return redirect('admin/slider/addslider')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay xóa slider!'
            );
        }
    }
    // Edit silder
    function editslider($id)
    {
        $slider = DB::table('sliders')->find($id);
        if ($slider->disabler != 'active') {
            return redirect('admin/slider/addslider')->with(
                'status',
                'Bạn không thể cập nhật slider sản phẩm đang vô hiệu hóa, bạn phải kích hoạt lại hoặc chọn slider sản phẩm khác đang kích hoạt để cập nhật!'
            );
        } else {
            $sliders = DB::table('sliders')->get();
            return view(
                'admin.slider.editslider',
                compact('slider', 'sliders')
            );
        }
    }
    // update silder
    function updateslider(Request $request, $id)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            $input = $request->all();
            // return $input;
            $request->validate(
                [
                    'name_slider' => 'required|unique:sliders',
                    'file' =>
                        'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ],
                [
                    'name_slider.required' =>
                        ':attribute slider không được để trống',
                    'required' => ':attribute slider',
                    'unique' =>
                        ':attribute slider đã tồn tại trong bảng sliders',
                    'image' => ':attribute có dạng file ảnh',
                    'mimes' => ':attribute có đuôi dạng jpeg,png,jpg,gif,svg',
                    'max' => ':attribute có dung lượng dưới 2048kb',
                ],
                [
                    'name_slider' => 'Tên ',
                    'file' => 'Phải chọn ảnh',
                ]
            );
            if ($request->hasFile('file')) {
                //    echo "Có file"."<br>";
                $file = $request->file; //Gán biến file vào $request:$request->file goi đến cái thuộc tính trong $request
                $fileName = $file->getClientOriginalName();
                // Xu ly trung ten file
                if (!file_exists('public/image/sliders/' . $fileName)) {
                    $path = $file->move(
                        'public/image/sliders',
                        $file->getClientOriginalName()
                    ); //Chuyển file lên server(trong folder public/uploads)
                    $image_slider = 'public/image/sliders/' . $fileName; //Đường dẫn của file lưu vào database
                } else {
                    $newfileName = time() . '-' . $fileName;
                    $path = $file->move('public/image/sliders', $newfileName); //Chuyển file lên server(trong folder public/uploads)
                    $image_slider = 'public/image/sliders/' . $newfileName; //Đường dẫn của file lưu vào database
                }
                $input['image_slider'] = $image_slider;
                //  echo $input['thumbnail'];
            }
            // Xoa anh cu
            $slider = DB::table('sliders')->find($id);
            if (file_exists($slider->image_slider)) {
                @unlink($slider->image_slider);
            }
            DB::table('sliders')
                ->where('id', $id)
                ->update([
                    'name_slider' => $input['name_slider'],
                    'image_slider' => $input['image_slider'],
                    'repairer' => Auth::id(),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ]);
            return redirect('admin/slider/addslider')->with(
                'status',
                'Đã cập nhật slider sản phẩm thành công!'
            );
        } else {
            return redirect('admin/slider/addslider')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay xóa slider!'
            );
        }
    }
    // disable slider
    function disableslider($id)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            $slider = DB::table('sliders')->find($id);
            if ($slider->disabler != 'active') {
                return redirect('admin/slider/addslider')->with(
                    'status',
                    'Slider này đã vô hiệu hóa rồi, bạn chỉ vô hiệu hóa slider sản phẩm đang kích hoạt thôi!'
                );
            } else {
                DB::table('sliders')
                    ->where('id', $id)
                    ->update([
                        'disabler' => Auth::id(),
                        'updated_at' => date('Y-m-d H:i:s', time()),
                    ]);
                return redirect('admin/slider/addslider')->with(
                    'status',
                    'Đã vô hiệu hóa slider sản phẩm thành công!'
                );
            }
        } else {
            return redirect('admin/slider/addslider')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay xóa slider!'
            );
        }
    }
    // Khoi phuc lai slider
    function restoreslider($id)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            $slider = DB::table('sliders')->find($id);
            if ($slider->disabler == 'active') {
                return redirect('admin/slider/addslider')->with(
                    'status',
                    'Slider sản phẩm này đang kích hoạt, bạn chỉ kích hoạt được khi slider đang vô hiệu hóa!'
                );
            } else {
                DB::table('sliders')
                    ->where('id', $id)
                    ->update([
                        'disabler' => 'active',
                        'updated_at' => date('Y-m-d H:i:s', time()),
                    ]);
                return redirect('admin/slider/addslider')->with(
                    'status',
                    'Đã kích hoạt lại slider sản phẩm thành công!'
                );
            }
        } else {
            return redirect('admin/slider/addslider')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay xóa slider!'
            );
        }
    }
    // Xoa sliceder
    function deleteslider($id)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            $file_slider = DB::table('sliders')->find($id);
            if (file_exists($file_slider->image_slider)) {
                @unlink($file_slider->image_slider);
            }
            $slider = DB::table('sliders')->where('id', $id);
            $slider->delete();
            return redirect('admin/slider/addslider')->with(
                'status',
                'Xóa slider sản phẩm thành công!'
            );
        } else {
            return redirect('admin/slider/addslider')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay xóa slider!'
            );
        }
    }
}
