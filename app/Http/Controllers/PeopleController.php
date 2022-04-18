<?php

namespace App\Http\Controllers;

use App\parentlist;
use App\product;
use App\productcat;
use Illuminate\Http\Request;
use Illuminate\Http\Response; //Phải khai báo thư viện này để dùng cookie
use Illuminate\Support\Facades\DB;

//Phải khai báo thằng này vào đây không sẽ lỗi
//Phải khai báo thư viện này vào đây mới dùng được các hàm
class PeopleController extends Controller
{
    // trang chu
    public function index(Request $request)
    {
        $sliders = DB::table('sliders')
            ->where('disabler', '=', 'active')
            ->get();
        // return $sliders;
        $parentlists = parentlist::where('disabler', '=', 'active')->get();

        // return $parentlists;
        // return $parentlists_old ;
        $productcats = productcat::where('disabler', '=', 'active')->get();
        // return $productcats;
        $products = product::where('disabler', '=', 'active')->get();
        // return $products;
        $banners = DB::table('advertisements')
            ->where('disabler', '=', 'active')
            ->get();
        $session = $request->session()->get('thongtin');
        if (!empty($session)) {
            foreach ($session as $key => $item) {
                $product_watch = product::where('disabler', '=', 'active')->where('id', '=', $item['id'])->get();
                // return count($product_watch);
                if (count($product_watch) == 0) {
                    // $request->session()->forget('username');//xóa 1 phần tử session
                    // echo "Bạn đã hủy session có tên : username";
                    // Xóa tất cả session
                    session()->forget($session[$key]); //xóa 1 phần tử session

                    // Xóa tất cả session
                    // $request->session()->flush();
                    // $request->session()->flush();
                    // unset($session[$key]);
                    break;
                }
            }
        }
        if (!empty($session)) {
            $uniques = array_unique($session);
            return view(
                'index',
                compact(
                    'sliders',
                    'productcats',
                    'products',
                    'parentlists',
                    'banners', 'uniques'
                )
            );
        } else {
            return view(
                'index',
                compact(
                    'sliders',
                    'productcats',
                    'products',
                    'parentlists',
                    'banners'
                )
            );
        }

    }
    // trang san pham
    public function products()
    {
        $sliders = DB::table('sliders')
            ->where('disabler', '=', 'active')
            ->get();
        // return $sliders;
        $parentlists = parentlist::where('disabler', '=', 'active')->get();

        // return $parentlists;
        // return $parentlists_old ;
        $productcats = productcat::where('disabler', '=', 'active')->get();
        // return $productcats;
        $products = product::where('disabler', '=', 'active')->get();
        // return $products;
        $banners = DB::table('advertisements')
            ->where('disabler', '=', 'active')
            ->get();
        return view(
            'products',
            compact(
                'sliders',
                'productcats',
                'products',
                'parentlists',
                'banners'
            )
        );
    }
    // trang danh muc cha
    public function category($slug)
    {
        $sliders = DB::table('sliders')
            ->where('disabler', '=', 'active')
            ->get();
        // return $sliders;
        $parentlists = parentlist::where('disabler', '=', 'active')->get();

        // return $parentlists;
        // return $parentlists_old ;
        $productcats = productcat::where('disabler', '=', 'active')->get();
        // return $productcats;
        $categoryparent = parentlist::where('slug', '=', $slug)->first();
        // return $categoryparent;
        $productcatscategory = productcat::where(
            'parentlist_id',
            '=',
            $categoryparent->id
        )
            ->where('disabler', '=', 'active')
            ->get();
        // return $productcatscategory;
        $products = product::where(
            'parentlistproduct_id',
            '=',
            $categoryparent->id
        )
            ->where('disabler', '=', 'active')
            ->get();
        // return $products;
        $banners = DB::table('advertisements')
            ->where('disabler', '=', 'active')
            ->get();
        return view(
            'categoryparent',
            compact(
                'sliders',
                'parentlists',
                'productcats',
                'productcatscategory',
                'products',
                'banners'
            )
        );
    }
    // trang danh muc san pham
    public function categoryproduct($slug)
    {
        $sliders = DB::table('sliders')
            ->where('disabler', '=', 'active')
            ->get();
        // return $sliders;
        $parentlists = parentlist::where('disabler', '=', 'active')->get();
        // return $parentlists;
        // return $parentlists_old ;
        $productcats = productcat::where('disabler', '=', 'active')->get();
        // return $productcats;
        $productcatcategory = productcat::where('slug', '=', $slug)->first();
        // return $productcat;
        $products = product::where(
            'productcat_id',
            '=',
            $productcatcategory->id
        )
            ->where('disabler', '=', 'active')
            ->get();
        // return $products;
        $banners = DB::table('advertisements')
            ->where('disabler', '=', 'active')
            ->get();
        return view(
            'categoryproduct',
            compact(
                'sliders',
                'parentlists',
                'productcats',
                'productcatcategory',
                'products',
                'banners'
            )
        );
    }
    // trang chi tiet san pham
    public function detailproduct(Request $request, $slug)
    {
        $parentlists = parentlist::where('disabler', '=', 'active')->get();
        $productcats = productcat::where('disabler', '=', 'active')->get();
        $product = product::where('slug_name', $slug)
            ->where('disabler', '=', 'active')
            ->first();
// Array
        // (
        //     [0] => 1
        //     [4] => 2
        //     [7] => 3
        //     [8] => 4
        //     [9] => 5
        // )
        //Lấy session thong tin

        // return $session;
        // foreach($session as $key=>$item){
        //     $product_session=product::where('id', $item->id)
        //     ->where('disabler', '=', 'active')
        //     ->first();
        //     // return count($product_session);
        //     if(empty($product_session)){
        //         unset($session[$key]);
        //     }
        // }
        // print_r($session);
        // return $request->session()->get('username'); //Lấy gia tri session
        // san pham cung danh muc
        $products_the_firm = product::where(
            'productcat_id',
            '=',
            $product->productcat_id
        )
            ->where('the_firm', '=', $product->the_firm)
            ->where('disabler', '=', 'active')
            ->get();
        $banners = DB::table('advertisements')
            ->where('disabler', '=', 'active')
            ->get();
        $request->session()->push('thongtin', $product);
        $session = $request->session()->get('thongtin');
        // $request->session()->put('username1', 'Phan Cuong');
        // return $request->session()->put('username1', 'Phan Cuong');
        // $request->session()->flush();
        // $session = $request->session()->all(); //Lấy hết session

        if (!empty($session)) {
            foreach ($session as $key => $item) {
                $product_watch = product::where('disabler', '=', 'active')->where('id', '=', $item['id'])->get();
                // return count($product_watch);
                if (count($product_watch) == 0) {
                    unset($item);
                    break;
                }
            }
        }
        // $array = [1, 1, 1, 1, 2, 2, 2, 3, 4, 5, 5];
        if (!empty($session)) {
            $uniques = array_unique($session);
            return view(
                'detailproduct',
                compact(
                    'product',
                    'products_the_firm',
                    'parentlists',
                    'productcats',
                    'banners', 'uniques'
                )
            );
        } else {
            return view(
                'detailproduct',
                compact(
                    'product',
                    'products_the_firm',
                    'parentlists',
                    'productcats',
                    'banners'
                )
            );
        }

    }
    public function search(Request $request)
    {
        $input = $request->all();
        // return $input;
        // return request()->search;
        $search = request()->search;
        $products = product::where('name', 'LIKE', "%{$search}%")
            ->orwhere('the_firm', 'LIKE', "%{$search}%")
            ->where('disabler', '=', 'active')
            ->get();
        // return $products;
        $parentlists = parentlist::where('disabler', '=', 'active')->get();
        $productcats = productcat::where('disabler', '=', 'active')->get();
        $sliders = DB::table('sliders')
            ->where('disabler', '=', 'active')
            ->get();
        // return $sliders;
        $banners = DB::table('advertisements')
            ->where('disabler', '=', 'active')
            ->get();
        return view(
            'search',
            compact(
                'parentlists',
                'productcats',
                'sliders',
                'products',
                'banners'
            )
        );
    }
}
