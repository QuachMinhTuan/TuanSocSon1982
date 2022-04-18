<?php

namespace App\Http\Controllers;

use App\parentlist;
use App\product;
use App\productcat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; //Phải khai báo thằng này vào đây không sẽ lỗi
use Illuminate\Support\Str;

//Phải khai báo thư viện này vào đây mới dùng được các hàm

class AdminProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // Su dung middleware toi uu cho active module_active
            //  Su dung middleware de rang buoc cai session khi di vao moi module, sesion se duoc thay doi theo thiet lap o moi module
            // Neu khong co middleware thi session luon lay cai dau tien la dashbord->khong dung yeu cau bai toan dat ra->HAY
            Session(['module_active' => 'product']);
            return $next($request);
        });
    }

    //Xay dung module product
    //1. Danh muc cha
    public function addcatparentcatproduct()
    {
        $catparents = parentlist::all();
        return view(
            'admin.product.add-cat-parent-cat-product',
            compact('catparents')
        );
    }
    public function storeaddcatparentcatproduct(Request $request)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            $input = $request->all();
            // return $request->all();
            $request->validate(
                [
                    'catparent' => 'required|max:50|min:3|unique:parentlists',
                ],
                [
                    'required' => ':attribute không được để trống',
                    'min' => ':attribute có độ dài ít nhât :min ký tự',
                    'max' => ':attribute có độ dài tối đa :max ký tự',
                    'unique' => ':attribute đã tồn tại trong bảng parentlists',
                ],
                [
                    'catparent' => 'Danh mục cha',
                ]
            );

            parentlist::create([
                'catparent' => $request->input('catparent'),
                'slug' => str::slug($request->input('catparent')),
                'creator' => Auth::id(),
                'disabler' => 'active',
            ]);
            return redirect('admin/product/cat/addcatparentcatproduct')->with(
                'status',
                'Thêm danh mục cha thành công!'
            );
        } else {
            return redirect('admin/product/cat/addcatparentcatproduct')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay xóa danh mục cha được!'
            );
        }
    }
    public function editcatparentcatproduct($id)
    {
        $catparent = parentlist::find($id);
        $catparents = parentlist::all();
        if ($catparent->disabler != 'active') {
            return redirect('admin/product/cat/addcatparentcatproduct')->with(
                'status',
                'Bạn chỉ cập nhật được danh mục cha khi danh mục đó đang ở trạng thái kích hoạt!'
            );
        } else {
            return view(
                'admin.product.edit-cat-parent-cat-product',
                compact('catparent', 'catparents')
            );
        }
    }
    public function updateparentcatproduct(Request $request, $id)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            $input = $request->all();
            // return $request->all();
            $catparent = parentlist::find($id);
            $request->validate(
                [
                    'catparent' => 'required|max:50|min:3|unique:parentlists',
                ],
                [
                    'required' => ':attribute không được để trống',
                    'min' => ':attribute có độ dài ít nhât :min ký tự',
                    'max' => ':attribute có độ dài tối đa :max ký tự',
                    'unique' => ':attribute đã tồn tại trong bảng parentlists',
                ],
                [
                    'catparent' => 'Danh mục cha',
                ]
            );
            // Cap nhat catparentcatproduct
            parentlist::where('id', $id)->update([
                'catparent' => $request->input('catparent'),
                'slug' => str::slug($request->input('catparent')),
                'repairer' => Auth::id(),
            ]);
            return redirect('admin/product/cat/addcatparentcatproduct')->with(
                'status',
                'Cập nhật danh mục cha thành công!'
            );
        } else {
            return redirect('admin/product/cat/addcatparentcatproduct')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay xóa danh mục cha được!'
            );
        }
    }
    public function disablecatparentcatproduct($id)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            $catparent = parentlist::find($id);

            if ($catparent->disabler != 'active') {
                return redirect(
                    'admin/product/cat/addcatparentcatproduct'
                )->with(
                    'status',
                    'Bạn chỉ vô hiệu hóa danh mục cha khi danh mục đó đang ở trạng thái kích hoạt!'
                );
            } else {
                $catproducts = productcat::where(
                    'parentlist_id',
                    '=',
                    $catparent->id
                )->get();
                foreach ($catproducts as $catproduct) {
                    $products = product::where(
                        'productcat_id',
                        '=',
                        $catproduct->id
                    )->get();
                    // vo hieu hoa san pham
                    foreach ($products as $item) {
                        // vo hieu hoa san pham
                        if ($item->disabler == 'active') {
                            $item->update(['disabler' => Auth::id()]);
                        }
                    }
                    // Vo hieu hoa danh muc san pham
                    if ($catproduct->disabler == 'active') {
                        $catproduct->update(['disabler' => Auth::id()]);
                    }
                }
                // Vo hieu hoa danh muc cha
                $catparent->update([
                    'disabler' => Auth::id(),
                ]);
                return redirect(
                    'admin/product/cat/addcatparentcatproduct'
                )->with('status', 'Vô hiệu hóa danh mục cha thành công!');
            }
        } else {
            return redirect('admin/product/cat/addcatparentcatproduct')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay xóa danh mục cha được!'
            );
        }
    }
    public function restorecatparentcatproduct($id)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            $catparent = parentlist::find($id);
            if ($catparent->disabler == 'active') {
                return redirect(
                    'admin/product/cat/addcatparentcatproduct'
                )->with(
                    'status',
                    'Bạn chỉ kích hoạt được danh mục cha khi danh mục đó đang ở trạng thái vô hiệu hóa!'
                );
            } else {
                $catparent->update([
                    'disabler' => 'active',
                ]);
                return redirect(
                    'admin/product/cat/addcatparentcatproduct'
                )->with('status', 'Kích hoạt lại danh mục cha thành công!');
            }
        } else {
            return redirect('admin/product/cat/addcatparentcatproduct')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay xóa danh mục cha được!'
            );
        }
    }
    public function deletecatparentcatproduct($id)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            $catparent = parentlist::find($id);
            $catproducts = productcat::where(
                'parentlist_id',
                '=',
                $catparent->id
            )->get();
            foreach ($catproducts as $catproduct) {
                $products = product::where(
                    'productcat_id',
                    '=',
                    $catproduct->id
                )->get();
                // xoa anh sp
                foreach ($products as $item) {
                    if (file_exists($item->thumbnail)) {
                        @unlink($item->thumbnail);
                    }
                    // xoa san pham o database
                    $item->delete();
                }
                // xoa danh muc san pham
                $catproduct->delete();
            }
            // xoa danh muc cha
            $catparent->delete();
            return redirect('admin/product/cat/addcatparentcatproduct')->with(
                'status',
                'Xóa vĩnh viễn danh mục cha thành công!'
            );
        } else {
            return redirect('admin/product/cat/addcatparentcatproduct')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay xóa danh mục cha được!'
            );
        }
    }

    // Them danh muc san pham
    public function addcatproduct()
    {
        $catproducts = productcat::all();
        $catparentlists = parentlist::where('disabler', '=', 'active')->get();
        return view(
            'admin.product.addcat',
            compact('catproducts', 'catparentlists')
        );
    }

    // Xu ly insert cat product vao bang danh muc san pham
    public function storeaddcatproduct(Request $request)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            // xu ly them danh muc san pham
            $input = $request->all();
            // return $request->all();
            $request->validate(
                [
                    'catname' => 'required|max:50|min:3|unique:productcats',
                    'parent_list_id' => 'required',
                ],
                [
                    'required' => ':attribute không được để trống',
                    'parent_list_id.required' => ':attribute danh mục cha',
                    'min' => ':attribute có độ dài ít nhât :min ký tự',
                    'max' => ':attribute có độ dài tối đa :max ký tự',
                    'unique' => ':attribute đã tồn tại trong bảng productcats',
                ],
                [
                    'catname' => 'Danh mục sản phẩm',
                    'parent_list_id' => 'Phải chọn',
                ]
            );

            productcat::create([
                'catname' => $request->input('catname'),
                'slug' => str::slug($request->input('catname')),
                'parentlist_id' => $request->input('parent_list_id'),
                'creator' => Auth::id(),
                'disabler' => 'active',
            ]);
            return redirect('admin/product/cat/addcatproduct')->with(
                'status',
                'Thêm danh mục sản phẩm thành công!'
            );
        } else {
            return redirect('admin/product/cat/addcatproduct')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay xóa danh mục sản phẩm!'
            );
        }
    }

    // Edit catproduct
    public function editcatproduct($id)
    {
        // xu ly edit danh muc san pham
        $editcatproduct = productcat::find($id);
        if ($editcatproduct->disabler != 'active') {
            return redirect('admin/product/cat/addcatproduct')->with(
                'status',
                'Bạn chỉ cập nhật được danh mục sản phẩm đang ở trạng thái kích hoạt!'
            );
        } else {
            $catproducts = productcat::all();
            $catparentlists = parentlist::where(
                'disabler',
                '=',
                'active'
            )->get();
            return view(
                'admin/product/editcatproduct',
                compact('catproducts', 'editcatproduct', 'catparentlists')
            );
        }
    }
    // Update catproduct
    public function updatecatproduct(Request $request, $id)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            $input = $request->all();
            // return $request->all();
            $catname = productcat::find($id)->catname;
            $request->validate(
                [
                    'catname' => 'required|max:50|min:3|unique:productcats',
                    'parent_list_id' => 'required',
                ],
                [
                    'required' => ':attribute không được để trống',
                    'parent_list_id.required' => ':attribute danh mục cha',
                    'min' => ':attribute có độ dài ít nhât :min ký tự',
                    'max' => ':attribute có độ dài tối đa :max ký tự',
                    'unique' => ':attribute đã tồn tại trong bảng productcats',
                ],
                [
                    'catname' => 'Danh mục sản phẩm',
                    'parent_list_id' => 'Phải chọn',
                ]
            );
            // Cap nhat catproduct
            productcat::where('id', $id)->update([
                'catname' => $request->input('catname'),
                'slug' => str::slug($request->input('catname')),
                'parentlist_id' => $request->input('parent_list_id'),
                'repairer' => Auth::id(),
            ]);
            return redirect('admin/product/cat/addcatproduct')->with(
                'status',
                'Cập nhật thành công danh mục sản phẩm!'
            );
        } else {
            return redirect('admin/product/cat/addcatproduct')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay xóa danh mục sản phẩm!'
            );
        }
    }
    // Vo hieu hoa danh muc san pham
    public function disablecatproduct($id)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            //  xu ly vo hieu hoa danh muc san pham
            $catproduct = productcat::find($id);
            if ($catproduct->disabler != 'active') {
                return redirect('admin/product/cat/addcatproduct')->with(
                    'status',
                    'Danh mục sản phẩm đã vô hiệu hóa, bạn chỉ vô hiệu hóa được khi danh mục đang kích hoạt!'
                );
            } else {
                $catproduct->update(['disabler' => Auth::id()]);
                $products = product::where('productcat_id', '=', $id)->get();
                foreach ($products as $item) {
                    if ($item->disabler == 'active') {
                        $item->update(['disabler' => Auth::id()]);
                    }
                }
                return redirect('admin/product/cat/addcatproduct')->with(
                    'status',
                    'Vô hiệu hóa danh mục sản phẩm và các sản phẩm của danh mục này thành công!'
                );
            }
        } else {
            return redirect('admin/product/cat/addcatproduct')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay xóa danh mục sản phẩm!'
            );
        }
    }
    // Kich hoat lai danh muc san pham
    public function restorecatproduct($id)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            //  xu ly kich hoat lai danh muc san pham
            $catproduct = productcat::find($id);
            if ($catproduct->disabler == 'active') {
                return redirect('admin/product/cat/addcatproduct')->with(
                    'status',
                    'Danh mục này đang kích hoạt, bạn chỉ kích hoạt được khi danh mục đang vô hiệu hóa!'
                );
            } else {
                // kich hoat lai danh muc san pham
                $catproduct->update(['disabler' => 'active']);
                // kich hoat lai danh muc cha
                $catparent = parentlist::find($catproduct->parentlist_id);
                if ($catparent->disabler != 'active') {
                    $catparent->update(['disabler' => 'active']);
                }
                // kich hoat lai san pham
                $products = product::where('productcat_id', '=', $id)->get();
                // return $products;
                foreach ($products as $item) {
                    if ($item->disabler != 'active') {
                        $item->update(['disabler' => 'active']);
                    }
                }
                return redirect('admin/product/cat/addcatproduct')->with(
                    'status',
                    'Kích hoạt danh mục sản phẩm và các sản phẩm của danh mục này thành công!'
                );
            }
        } else {
            return redirect('admin/product/cat/addcatproduct')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay xóa danh mục sản phẩm!'
            );
        }
    }
    // Xoa danh muc san pham
    public function deletecatproduct($id)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            // xu ly xoa danh muc san pham
            // xoa sp truoc
            $products = product::where('productcat_id', '=', $id)->get();
            // xoa anh sp
            foreach ($products as $item) {
                if (file_exists($item->thumbnail)) {
                    @unlink($item->thumbnail);
                }
                // xoa san pham o database
                $item->delete();
            }
            // xoa danh muc san pham sau
            $deletecatproduct = productcat::find($id)->delete();
            return redirect('admin/product/cat/addcatproduct')->with(
                'status',
                'Xóa vĩnh viễn danh mục sản phẩm và các sản phẩm của danh mục này thành công!'
            );
        } else {
            return redirect('admin/product/cat/addcatproduct')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay xóa danh mục sản phẩm!'
            );
        }
    }

    // action danh muc san pham
    public function listproduct(Request $request)
    {
        // xu ly hien view danh sach san pham
        $status = request()->input('status');
        $list_act = [
            'delete' => 'Vô hiệu hóa',
        ];
        if ($status == 'trash') {
            $list_act = [
                'restore' => 'Kích hoạt',
                'forceDelete' => 'Xóa vĩnh viễn',
            ];
            // $listproducts = product::onlyTrashed()->paginate(16);
            $listproducts = product::where(
                'disabler',
                '<>',
                'active'
            )->paginate(16);
        } else {
            $keyword = '';
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }
            $listproducts = product::where('name', 'LIKE', "%{$keyword}%")
                ->where('disabler', '=', 'active')
                ->paginate(16);
        }

        $count_product_active = product::where(
            'disabler',
            '=',
            'active'
        )->count();
        // $count_product_active = product::count();
        $count_product_trash = product::where(
            'disabler',
            '<>',
            'active'
        )->count();
        $count = [$count_product_active, $count_product_trash];
        return view(
            'admin/product/listproduct',
            compact('listproducts', 'count', 'list_act')
        );
    }
    // Them mau sac san pham
    public function addcolorproduct()
    {
        $colors = DB::table('product_colors')->get();
        return view('admin.product.add-color-product', compact('colors'));
    }
    // Xu ly them mau sac san pham
    public function storeaddcolorproduct(Request $request)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            // return request()->all();
            // return request()->namecolor;
            $request->validate(
                [
                    'namecolor' => 'required|max:50|unique:product_colors',
                ],
                [
                    'required' => ':attribute không được để trống',
                    'max' => ':attribute có độ dài tối đa :max ký tự',
                    'unique' =>
                    ':attribute đã tồn tại trong bảng product_colors',
                ],
                [
                    'namecolor' => 'Màu sản phẩm',
                ]
            );
            DB::table('product_colors')->insert([
                'namecolor' => request()->namecolor,
                'creator' => Auth::id(),
                'disabler' => 'active',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]);
            return redirect('admin/product/addcolorproduct')->with(
                'status',
                'Thêm màu sản phẩm thành công!'
            );
        } else {
            return redirect('admin/product/addcolorproduct')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay xóa màu sản phẩm!'
            );
        }
    }
    // Edit mau san pham
    public function editcolorproduct($id)
    {
        $color = DB::table('product_colors')->find($id);
        $colors = DB::table('product_colors')->get();
        return view(
            'admin.product.editcolorproduct',
            compact('color', 'colors')
        );
    }
    // Cap nhat mau san pham
    public function updatecolorproduct(Request $request, $id)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            $request->validate(
                [
                    'namecolor' => 'required|max:50|unique:product_colors',
                ],
                [
                    'required' => ':attribute không được để trống',
                    'max' => ':attribute có độ dài tối đa :max ký tự',
                    'unique' =>
                    ':attribute đã tồn tại trong bảng product_colors',
                ],
                [
                    'namecolor' => 'Màu sản phẩm',
                ]
            );
            DB::table('product_colors')
                ->where('id', '=', $id)
                ->update([
                    'namecolor' => request()->namecolor,
                    'repairer' => Auth::id(),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ]);
            return redirect('admin/product/addcolorproduct')->with(
                'status',
                'Cập nhật màu sản phẩm thành công!'
            );
        } else {
            return redirect('admin/product/addcolorproduct')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay xóa màu sản phẩm!'
            );
        }
    }
    // Xoa vinh vien mau san pham
    public function deletecolorproduct($id)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            DB::table('product_colors')
                ->where('id', '=', $id)
                ->delete();
            return redirect('admin/product/addcolorproduct')->with(
                'status',
                'Xóa vĩnh viễn màu sản phẩm thành công!'
            );
        } else {
            return redirect('admin/product/addcolorproduct')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay xóa màu sản phẩm!'
            );
        }
    }
    // Them hang san pham
    public function add_company_product()
    {
        // Quyen truy cap cua user
        // $roles = [];
        // foreach (Auth::user()->roles as $role) {
        //     if ($role->namerole == 'editproduct') {
        //         $roles['editproduct'] = 'editproduct';
        //     }
        //     if ($role->namerole == 'addproduct') {
        //         $roles['addproduct'] = 'addproduct';
        //     }
        //     if ($role->namerole == 'administrators') {
        //         $roles['administrators'] = 'administrators';
        //     }
        // }
        // return $roles;
        // if (empty($roles)) {
        //     return redirect('admin')->with(
        //         'status',
        //         'Bạn không được phép truy cập vào trang thêm hãng sản phẩm!'
        //     );
        // }
        $companys = DB::table('product_companys')->get();
        return view('admin.product.add-company-product', compact('companys'));
    }
    // Xu ly them hang san pham
    public function storeaddcompanyproduct(Request $request)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            // return request()->all();
            // return request()->namecolor;
            $request->validate(
                [
                    'namecompany' => 'required|max:50|unique:product_companys',
                ],
                [
                    'required' => ':attribute không được để trống',
                    'max' => ':attribute có độ dài tối đa :max ký tự',
                    'unique' =>
                    ':attribute đã tồn tại trong bảng product_companys',
                ],
                [
                    'namecompany' => 'Hãng sản phẩm',
                ]
            );
            DB::table('product_companys')->insert([
                'namecompany' => request()->namecompany,
                'slug_namecompany' => str::slug(request()->namecompany),
                'creator' => Auth::id(),
                'disabler' => 'active',
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
            ]);
            return redirect('admin/product/add_company_product')->with(
                'status',
                'Thêm hãng sản phẩm thành công!'
            );
        } else {
            return redirect('admin/product/add_company_product')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay xóa hãng sản phẩm!'
            );
        }
    }
    // Edit hang san pham
    public function edit_company_product($id)
    {
        $company = DB::table('product_companys')->find($id);
        $companys = DB::table('product_companys')->get();
        return view(
            'admin.product.edit_company_product',
            compact('company', 'companys')
        );
    }
    // Cap nhat hang san pham
    public function update_company_product(Request $request, $id)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            $request->validate(
                [
                    'namecompany' => 'required|max:50|unique:product_companys',
                ],
                [
                    'required' => ':attribute không được để trống',
                    'max' => ':attribute có độ dài tối đa :max ký tự',
                    'unique' =>
                    ':attribute đã tồn tại trong bảng product_company',
                ],
                [
                    'namecompany' => 'Hãng sản phẩm',
                ]
            );
            DB::table('product_companys')
                ->where('id', '=', $id)
                ->update([
                    'namecompany' => request()->namecompany,
                    'slug_namecompany' => str::slug(request()->namecompany),
                    'repairer' => Auth::id(),
                    'updated_at' => date('Y-m-d H:i:s', time()),
                ]);
            return redirect('admin/product/add_company_product')->with(
                'status',
                'Cập nhật hãng sản phẩm thành công!'
            );
        } else {
            return redirect('admin/product/add_company_product')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay xóa hãng sản phẩm!'
            );
        }
    }
    //  Xoa vinh vien hang san pham
    public function delete_company_product($id)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            DB::table('product_companys')
                ->where('id', '=', $id)
                ->delete();
            return redirect('admin/product/add_company_product')->with(
                'status',
                'Xóa vĩnh viễn hãng sản phẩm thành công!'
            );
        } else {
            return redirect('admin/product/add_company_product')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay xóa hãng sản phẩm!'
            );
        }
    }
    // Them san pham
    public function addproduct()
    {
        // xu ly hien thi view them san pham
        $catproducts = productcat::where('disabler', '=', 'active')->get();
        $companys = DB::table('product_companys')->get();
        // return $company;
        $colors = DB::table('product_colors')->get();
        return view(
            'admin.product.addproduct',
            compact('catproducts', 'companys', 'colors')
        );
    }
    public function storeproduct(Request $request)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            // $page=new Page;
            // $page=$request->all();
            // $input=$request->all();
            // return $request->all();
            // return $request->input('file');
            // return request()->product_speak;
            $request->validate(
                [
                    'masp' => 'required|string|max:50|unique:products',
                    'shortened_link' => 'required|string|unique:products',
                    'file' =>
                    'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    // 'file' => 'required|image',
                    'name' => 'required|string|min:3|max:200',
                    'price' => 'required|max:20',
                    'product-details' => 'required|min:8',
                    'description' => 'required|min:8',
                    'product_id' => 'required',
                ],
                [
                    'required' => ':attribute không được để trống',
                    'product_id.required' => ':attribute danh mục sản phẩm',
                    'file.required' => ':attribute ảnh sản phẩm',
                    'min' => ':attribute có độ dài ít nhất :min ký tự',
                    'max' => ':attribute có độ dài tối đa :max ký tự',
                    'unique' => ':attribute phải duy nhất',
                    'image' => ':attribute ảnh có dạng file ảnh',
                    'integer' => ':attribute có dạng số nguyên',
                    'mimes' =>
                    ':attribute ảnh có đuôi dạng jpeg,png,jpg,gif,svg',
                    'file.max' => ':attribute ảnh có dung lượng dưới 2048kb',
                ],
                [
                    'masp' => 'Mã sản phẩm',
                    'shortened_link' => 'Link liên kết rút gọn',
                    'file' => 'Phải chọn',
                    'name' => 'Tên sản phẩm',
                    'price' => 'Giá sản phẩm',
                    // 'color' => 'Bạn phải chọn',
                    'product-details' => 'Chi tiết sản phẩm',
                    'description' => 'Mô tả sản phẩm',
                    'product_id' => 'Phải chọn',
                    // 'the_firm' => 'Bạn phải chọn',
                ]
            );
            if ($request->hasFile('file')) {
                //    echo "Có file"."<br>";
                $file = $request->file; //Gán biến file vào $request:$request->file goi đến cái thuộc tính trong $request
                // echo $file;
                // Lấy tên file
                $fileName = $file->getClientOriginalName();
                //   echo $file->getClientOriginalName();
                //   echo "<br>";
                //   Lay ten file khong co duoi
                // echo pathinfo($fileName, PATHINFO_FILENAME)."<br>";
                //   echo 'public/products/'.$file->getClientOriginalName();
                // Lấy đuôi file
                // echo  "Duoi file : ".$file->getClientOriginalExtension()."<br>";

                // Xu ly trung ten file
                if (!file_exists('public/image/products/' . $fileName)) {
                    $path = $file->move(
                        'public/image/products',
                        $file->getClientOriginalName()
                    ); //Chuyển file lên server(trong folder public/uploads)
                    $thumbnail = 'public/image/products/' . $fileName; //Đường dẫn của file lưu vào database
                } else {
                    $newfileName = time() . '-' . $fileName;
                    $path = $file->move('public/image/products', $newfileName); //Chuyển file lên server(trong folder public/uploads)
                    $thumbnail = 'public/image/products/' . $newfileName; //Đường dẫn của file lưu vào database
                }

                $input['thumbnail'] = $thumbnail;
            }
            $color_input = $request->input('color');
            if ($color_input != '') {
                $color = DB::table('product_colors')->find($color_input)
                    ->namecolor;
            } else {
                $color = '';
            }
            $the_firm_input = $request->input('the_firm');
            if ($the_firm_input != '') {
                $the_firm = DB::table('product_companys')->find(
                    $request->input('the_firm')
                )->namecompany;
            } else {
                $the_firm = '';
            }

            product::create([
                'masp' => $request->input('masp'),
                'shortened_link' => $request->input('shortened_link'),
                'thumbnail' => $input['thumbnail'],
                'name' => $request->input('name'),
                'slug_name' => str::slug($request->input('name')),
                'color' => $color,
                'price' => $request->input('price'),
                'product_information' => $request->input('product-details'),
                'description' => $request->input('description'),
                'the_firm' => $the_firm,
                'slug_the_firm' => str::slug($the_firm),
                'creator' => Auth::id(),
                'disabler' => 'active',
                'productcat_id' => $request->input('product_id'),
                'parentlistproduct_id' => productcat::find(
                    $request->input('product_id')
                )->parentlist_id,
            ]);
            return redirect('admin/product/listproduct')->with(
                'status',
                'Thêm sản phẩm thành công!'
            );
        } else {
            return redirect('admin/product/listproduct')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay xóa sản phẩm!'
            );
        }
    }

    // Edit san pham
    public function editproduct($id)
    {
        $product = product::find($id);
        $productcats = productcat::where('disabler', '=', 'active')->get();
        $companys = DB::table('product_companys')->get();
        // return $company;
        $colors = DB::table('product_colors')->get();
        return view(
            'admin/product/editproduct',
            compact('product', 'productcats', 'companys', 'colors')
        );
    }
    public function updateproduct(Request $request, $id)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            $request->validate(
                [
                    'shortened_link' => 'required|string|unique:products',
                    'file' =>
                    'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    // 'file' => 'required|image',
                    'name' => 'required|string|min:3|max:200',
                    'price' => 'required|max:20',
                    // 'color' => 'required',
                    'product-details' => 'required|min:8',
                    'description' => 'required|min:8',
                    'product_id' => 'required',
                    // 'the_firm' => 'required|string|max:50',
                ],
                [
                    'required' => ':attribute không được để trống',
                    'product_id.required' => ':attribute danh mục sản phẩm',
                    // 'color.required' => ':attribute màu sản phẩm',
                    // 'the_firm.required' => ':attribute hãng sản phẩm',
                    'file.required' => ':attribute ảnh sản phẩm',
                    'min' => ':attribute có độ dài ít nhất :min ký tự',
                    'max' => ':attribute có độ dài tối đa :max ký tự',
                    'unique' => ':attribute phải duy nhất',
                    'image' => ':attribute ảnh có dạng file ảnh',
                    'integer' => ':attribute có dạng số nguyên',
                    'mimes' =>
                    ':attribute ảnh có đuôi dạng jpeg,png,jpg,gif,svg',
                    'file.max' => ':attribute ảnh có dung lượng dưới 2048kb',
                ],
                [
                    'masp' => 'Mã sản phẩm',
                    'shortened_link' => 'Link liên kết rút gọn',
                    'file' => 'Phải chọn',
                    'name' => 'Tên sản phẩm',
                    'price' => 'Giá sản phẩm',
                    // 'color' => 'Bạn phải chọn',
                    'product-details' => 'Chi tiết sản phẩm',
                    'description' => 'Mô tả sản phẩm',
                    'product_id' => 'Phải chọn',
                    // 'the_firm' => 'Bạn phải chọn',
                ]
            );
            if ($request->hasFile('file')) {
                $file = $request->file; //Gán biến file vào $request:$request->file goi đến cái thuộc tính trong $request
                // echo $file;
                // Lấy tên file
                $fileName = $file->getClientOriginalName();
                //   echo $file->getClientOriginalName();
                if (!file_exists('public/image/products/' . $fileName)) {
                    $path = $file->move(
                        'public/image/products',
                        $file->getClientOriginalName()
                    ); //Chuyển file lên server(trong folder public/uploads)
                    $thumbnail = 'public/image/products/' . $fileName; //Đường dẫn của file lưu vào database
                } else {
                    $newfileName = time() . '-' . $fileName;
                    $path = $file->move('public/image/products', $newfileName); //Chuyển file lên server(trong folder public/uploads)
                    $thumbnail = 'public/image/products/' . $newfileName; //Đường dẫn của file lưu vào database
                }
                // echo $path;
                //  $thumbnail='public/products/'.$fileName; //Đường dẫn của file lưu vào database
                //  echo $thumbnail;
                $input['thumbnail'] = $thumbnail; //Đường dẫn của file lưu vào database
            }
            // Xoa anh cu
            $path_image_product = product::find($id);
            if (file_exists($path_image_product->thumbnail)) {
                @unlink($path_image_product->thumbnail);
            }
            $color_input = $request->input('color');
            if ($color_input != '') {
                $color = DB::table('product_colors')->find($color_input)
                    ->namecolor;
            } else {
                $color = '';
            }
            $the_firm_input = $request->input('the_firm');
            if ($the_firm_input != '') {
                $the_firm = DB::table('product_companys')->find(
                    $request->input('the_firm')
                )->namecompany;
            } else {
                $the_firm = '';
            }

            // $the_firm = DB::table('product_companys')->find(
            //     $request->input('the_firm')
            // )->namecompany;
            product::where('id', $id)->update([
                // 'masp'=>$request->input('masp'),
                'shortened_link' => $request->input('shortened_link'),
                'thumbnail' => $input['thumbnail'],
                'name' => $request->input('name'),
                'slug_name' => str::slug($request->input('name')),
                'price' => $request->input('price'),
                'color' => $color,
                'product_information' => $request->input('product-details'),
                'description' => $request->input('description'),
                'productcat_id' => $request->input('product_id'),
                'parentlistproduct_id' => productcat::find(
                    $request->input('product_id')
                )->parentlist_id,
                'the_firm' => $the_firm,
                'slug_the_firm' => str::slug($the_firm),
                'repairer' => Auth::id(),
            ]);

            return redirect('admin/product/listproduct')->with(
                'status',
                'Cập nhật sản phẩm thành công!'
            );
        } else {
            return redirect('admin/product/listproduct')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay xóa sản phẩm!'
            );
        }
    }
    // Vo hieu hoa san pham
    public function disableproduct($id)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            //  xu ly vo hieu hoa san pham
            $product = product::find($id);
            $product->update(['disabler' => Auth::id()]);
            return redirect('admin/product/listproduct')->with(
                'status',
                'Vô hiệu hóa sản phẩm thành công!'
            );
        } else {
            return redirect('admin/product/listproduct')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay xóa sản phẩm!'
            );
        }
    }
    // Kich hoat lai san pham
    public function restoreproduct($id)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            $product = product::find($id);
            $product->update(['disabler' => 'active']);
            $catproduct = productcat::find($product->productcat_id);
            // khoi phuc danh muc san pham
            if ($catproduct->disabler != 'active') {
                $catproduct->update(['disabler' => 'active']);
            }
            $catparentproduct = parentlist::find($catproduct->parentlist_id);
            // khoi phuc danh muc danh muc cha
            if ($catparentproduct->disabler != 'active') {
                $catparentproduct->update(['disabler' => 'active']);
            }
            return redirect('admin/product/listproduct')->with(
                'status',
                'Kích hoạt lại sản phẩm thành công!'
            );
        } else {
            return redirect('admin/product/listproduct')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay xóa sản phẩm!'
            );
        }
    }

    // Xoa san pham
    public function deleteproduct($id)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            // xu ly xoa vinh vien san pham
            $product = product::find($id);
            // xoa file
            $file_product = $product->thumbnail;
            if (file_exists($file_product)) {
                @unlink($file_product);
            }
            $product->delete();
            return redirect('admin/product/listproduct')->with(
                'status',
                'Xóa vĩnh viễn sản phẩm thành công!'
            );
        } else {
            return redirect('admin/product/listproduct')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay xóa sản phẩm!'
            );
        }
    }
    // Thuc hien tren nhieu ban ghi khac nhau
    public function actionproduct(Request $request)
    {
        // Xu ly quyen user
        $roles = DB::table('rolesusers')
            ->where('user_id', '=', Auth::id())
            ->where('nameRole', '=', 'admintrator')
            ->get();
        // return $roles;
        if (count($roles) > 0) {
            // xu ly tren nhieu ban ghi khac nhau
            $list_check = $request->input('list_check');
            // return $list_check;
            if (isset($list_check)) {
                //Kiem tra $list_check da duoc tao thi
                if (!empty($list_check)) {
                    $act = $request->input('act');
                    if ($act == 'delete') {
                        // Cap nhat san pham cho them user xoa vao
                        product::whereIn('id', $list_check)->update([
                            'disabler' => Auth::id(),
                        ]);
                        // Xoa tam thoi sp
                        // product::destroy($list_check);
                        return redirect('admin/product/listproduct')->with(
                            'status',
                            'Vô hiệu hóa các sản phẩm thành công!'
                        );
                    }
                    if ($act == 'restore') {
                        // Lay danh muc san pham duy nhat
                        $list_productcat_id = product::whereIn(
                            'id',
                            $list_check
                        )
                            ->get('productcat_id')
                            ->unique('productcat_id');
                        //return $list_productcat_id;
                        $list_id_productcat = [];
                        foreach ($list_productcat_id as $item) {
                            $list_id_productcat[] = $item->productcat_id;
                        }
                        //    return $list_id_productcat;
                        // Khoi phuc lai danh muc san pham truoc
                        $productcats = productcat::whereIn(
                            'id',
                            $list_id_productcat
                        )->get();
                        foreach ($productcats as $item) {
                            if ($item->disabler != 'active') {
                                $item->update(['disabler' => 'active']);
                            }
                            //khoi phuc lai danh muc cha
                            if (
                                parentlist::find($item->parentlist_id)
                                ->disabler != 'active'
                            ) {
                                parentlist::find($item->parentlist_id)->update([
                                    'disabler' => 'active',
                                ]);
                            }
                        }
                        // Khoi phuc lai san pham
                        product::whereIn('id', $list_check)->update([
                            'disabler' => 'active',
                        ]);
                        return redirect('admin/product/listproduct')->with(
                            'status',
                            'Bạn đã khôi phục các danh mục sản phẩm và các sản phẩm thành công!'
                        );
                    }
                    // Phan 24 bai 277 : Xoa vinh vien san pham
                    if ($act == 'forceDelete') {
                        // Xoa anh san pham
                        $products = product::whereIn('id', $list_check)->get();
                        foreach ($products as $item) {
                            if (file_exists($item->thumbnail)) {
                                @unlink($item->thumbnail);
                            }
                        }
                        // Xoa o database
                        product::whereIn('id', $list_check)->delete();
                        return redirect('admin/product/listproduct')->with(
                            'status',
                            'Bạn đã xóa vĩnh viễn các sản phẩm thành công!'
                        );
                    }
                }
                return redirect('admin/product/listproduct')->with(
                    'status',
                    'Bạn phải chọn hình thức vô hiệu hóa, xóa vĩnh viễn hoặc khôi phục!'
                );
            } else {
                return redirect('admin/product/listproduct')->with(
                    'status',
                    'Bạn cần chọn phần tử cần thực hiện!'
                );
            }
        } else {
            return redirect('admin/product/listproduct')->with(
                'status',
                'Bạn không được phép thêm, sửa, vô hiệu hóa, kích hoạt hay xóa sản phẩm!'
            );
        }
    }
}
