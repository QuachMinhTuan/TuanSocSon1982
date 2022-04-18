<?php

use Illuminate\Support\Facades\Route;
// Khai bao thu vien auth khoi bi loi
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
// Phan nguoi dung
Route::get('/', 'PeopleController@index')->name('index');
Route::get('/products', 'PeopleController@products')->name('products');
Route::get('category/{slug}', 'PeopleController@category')->name('category');
Route::get('/products/{slug}', 'PeopleController@categoryproduct')->name(
    'categoryproduct'
);
Route::get('/chi-tiet-san-pham/{slug}', 'PeopleController@detailproduct')->name(
    'detailproduct'
);
Route::get('/search', 'PeopleController@search')->name('search');

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware('auth', 'verified')->group(function () {
    // =====================module dashboard===========================
    // Route::get('dashboard', 'DashboardController@show')->name('dashboard'); //Khi nguoi dung chua login ma vao trang dashboard thi ta phai rang buoc ho khong se bao loi bang middleware
    Route::get('dashboard', 'DashboardController@show'); //Khi nguoi dung chua login ma vao trang dashboard thi ta phai rang buoc ho khong se bao loi bang middleware
    // On lai bai xu ly logout chuyen den login
    // Route::get('logout', 'DashboardController@logout'); //Xu ly dc nhung khoang thoat han tai khoan login duoc
    // Phan 24 bai 267 :them duong dan admin sau chuyen huong ve dashboard
    Route::get('admin', 'DashboardController@show'); //Thuong lam trang unimart thuong them duong dan admin dang sau
    // Xu ly nhieu ban ghi trang dashboah
    //     Route::get('admin/dashboard/deletecustomer/{id}', 'DashboardController@deletecustomer')->name('dashboard.deletecustomer'); //Thuong lam trang unimart thuong them duong dan admin dang sau
    //     Route::get('admin/dashboard/action', 'DashboardController@action')->name('dashboard.action'); //Thuong lam trang unimart thuong them duong dan admin dang sau
    // =========================end module dashboard========================
    // =====================module user===================================
    //     // Phan 24 bai 268 : Hien thi danh sach quan tri vien
    Route::get('admin/user/list', 'AdminUserController@list'); //->middleware('auth'); Thu cong cho rang buoc middleware de khoi loi khi nguoi dung co tinh truy cap vao admin

    //     // Phan 24 bai 271 : them user
    Route::get('admin/user/add', 'AdminUserController@add'); //->middleware('auth');
    //     // Phan 24 bai 271 : them user
    Route::post('admin/user/store', 'AdminUserController@store'); //->middleware('auth');

    //     // Phan 24 bai 274 : Xoa user khoi he thong
    Route::get('admin/user/delete/{id}', 'AdminUserController@delete')->name(
        'delete_user'
    );
    //     // Phan 24 bai 276 : Thuc hien tac vu tren nhieu ban ghi
    Route::get('admin/user/action', 'AdminUserController@action'); //->middleware('auth');

    //      // Phan 24 bai 278 : Cap nhat thong tin nguoi dung
    Route::get('admin/user/edit/{id}', 'AdminUserController@edit')->name(
        'user.edit'
    ); //->middleware('auth');
    //      // Phan 24 bai 278 : Cap nhat thong tin nguoi dung
    Route::post('admin/user/update/{id}', 'AdminUserController@update')->name(
        'user.update'
    );

    // ===================== end module user=================

    //   Module Product
    // Danh muc cha
    // Them danh muc san pham
    Route::get(
        'admin/product/cat/addcatparentcatproduct',
        'AdminProductController@addcatparentcatproduct'
    );
    Route::post(
        'admin/product/storeaddcatparentcatproduct',
        'AdminProductController@storeaddcatparentcatproduct'
    );
    // Cap nhat danh muc cha
    Route::get(
        'admin/product/cat/editcatparentcatproduct/{id}',
        'AdminProductController@editcatparentcatproduct'
    )->name('edit_cat_parent_cat_product');
    Route::get(
        'admin/product/cat/updateparentcatproduct/{id}',
        'AdminProductController@updateparentcatproduct'
    )->name('update_cat_parent_cat_product');
    // Vo hieu hoa danh muc cha
    Route::get(
        'admin/product/cat/disablecatparentcatproduct/{id}',
        'AdminProductController@disablecatparentcatproduct'
    )->name('disable_cat_parent_cat_product');
    // Kich hoat lai danh muc cha
    Route::get(
        'admin/product/cat/restorecatparentcatproduct/{id}',
        'AdminProductController@restorecatparentcatproduct'
    )->name('restore_cat_parent_cat_product');
    // Xoa vinh vien danh muc cha
    Route::get(
        'admin/product/cat/deletecatparentcatproduct/{id}',
        'AdminProductController@deletecatparentcatproduct'
    )->name('delete_cat_parent_cat_product');
    //   Danh muc san pham
    // Them danh muc san pham
    Route::get(
        'admin/product/cat/addcatproduct',
        'AdminProductController@addcatproduct'
    );
    Route::post(
        'admin/product/storeaddcatproduct',
        'AdminProductController@storeaddcatproduct'
    );
    // Cap nhat danh muc san pham
    Route::get(
        'admin/product/cat/editcatproduct/{id}',
        'AdminProductController@editcatproduct'
    )->name('edit_cat_product');
    Route::get(
        'admin/product/cat/updatecatproduct/{id}',
        'AdminProductController@updatecatproduct'
    )->name('update_cat_product');
    // Vo hieu hoa danh muc san pham
    Route::get(
        'admin/product/cat/disablecatproduct/{id}',
        'AdminProductController@disablecatproduct'
    )->name('disablecatproduct');
    // Kich hoat lai danh muc san pham
    Route::get(
        'admin/product/cat/restorecatproduct/{id}',
        'AdminProductController@restorecatproduct'
    )->name('restorecatproduct');
    // Xoa vinh vien danh muc san pham
    Route::get(
        'admin/product/cat/deletecatproduct/{id}',
        'AdminProductController@deletecatproduct'
    )->name('delete_cat_product');
    // San pham
    // Them mau sac san pham
    Route::get(
        'admin/product/addcolorproduct',
        'AdminProductController@addcolorproduct'
    )->name('add_color_product');
    Route::post(
        'admin/product/storeaddcolorproduct',
        'AdminProductController@storeaddcolorproduct'
    );
    // Edit mau sac san pham
    Route::get(
        'admin/product/editcolorproduct/{id}',
        'AdminProductController@editcolorproduct'
    )->name('edit_color_product');
    // Edit mau sac san pham
    Route::post(
        'admin/product/updatecolorproduct/{id}',
        'AdminProductController@updatecolorproduct'
    )->name('update_color_product');
    // Xoa vinh vien mau sac san pham
    Route::get(
        'admin/product/deletecolorproduct/{id}',
        'AdminProductController@deletecolorproduct'
    )->name('delete_color_product');
    // Them hang san pham
    Route::get(
        'admin/product/add_company_product',
        'AdminProductController@add_company_product'
    )->name('add_company_product');
    Route::post(
        'admin/product/storeaddcompanyproduct',
        'AdminProductController@storeaddcompanyproduct'
    );
    // Edit hang san pham
    Route::get(
        'admin/product/edit_company_product/{id}',
        'AdminProductController@edit_company_product'
    )->name('edit_company_product');
    // Cap nhat hang san pham
    Route::post(
        'admin/product/update_company_product/{id}',
        'AdminProductController@update_company_product'
    )->name('update_company_product');
    // Xoa vinh vien hang san pham
    Route::get(
        'admin/product/delete_company_product/{id}',
        'AdminProductController@delete_company_product'
    )->name('delete_company_product');
    // Them san pham
    Route::get(
        'admin/product/addproduct',
        'AdminProductController@addproduct'
    )->name('addp_roduct');
    Route::post(
        'admin/product/storeproduct',
        'AdminProductController@storeproduct'
    );
    // Edit san pham
    Route::get(
        'admin/product/editproduct/{id}',
        'AdminProductController@editproduct'
    )->name('edit_product');
    Route::post(
        'admin/product/updateproduct/{id}',
        'AdminProductController@updateproduct'
    );
    // Vo hieu hoa san pham
    Route::get(
        'admin/product/disableproduct/{id}',
        'AdminProductController@disableproduct'
    )->name('disable_product');
    // Kich hoat san pham
    Route::get(
        'admin/product/restoreproduct/{id}',
        'AdminProductController@restoreproduct'
    )->name('restore_product');
    // Xoa vinh vien san pham
    Route::get(
        'admin/product/deleteproduct/{id}',
        'AdminProductController@deleteproduct'
    )->name('delete_product');
    // Thuc hien tac vu tren nhieu ban ghi
    Route::get(
        'admin/product/actionproduct',
        'AdminProductController@actionproduct'
    );
    // Hien danh sach san pham
    Route::get(
        'admin/product/listproduct',
        'AdminProductController@listproduct'
    );
    //======================= end module product==================
    // ========================module slider=======================
    //     // Module slider
    // them slider
    Route::get('admin/slider/addslider', 'AdminSliderController@addslider');
    Route::post(
        'admin/slider/addstoreslider',
        'AdminSliderController@addstoreslider'
    );
    // edit slider
    Route::get(
        'admin/slider/editslider/{id}',
        'AdminSliderController@editslider'
    )->name('edit_slider');
    Route::post(
        'admin/slider/updateslider/{id}',
        'AdminSliderController@updateslider'
    )->name('update_slider');
    // disable slider
    Route::get(
        'admin/slider/disableslider/{id}',
        'AdminSliderController@disableslider'
    )->name('disable_slider');
    // restore slider
    Route::get(
        'admin/slider/restoreslider/{id}',
        'AdminSliderController@restoreslider'
    )->name('restore_slider');
    // xoa vinh vien slider
    Route::get(
        'admin/slider/deleteslider/{id}',
        'AdminSliderController@deleteslider'
    )->name('delete_slider');
    // ========================end module slider=============================
    // ===========================module quang cao============================
    //    //  Module quang cao
    Route::get(
        'admin/advertisement/addadvertisement',
        'AdminadvertisementController@addadvertisement'
    )->name('add_advertisement');
    // xu ly them quang cao
    Route::post(
        'admin/advertisement/storeadvertisement',
        'AdminadvertisementController@storeadvertisement'
    );
    // xu ly edit quang cao
    Route::get(
        'admin/advertisement/editadvertisement/{id}',
        'AdminadvertisementController@editadvertisement'
    )->name('edit_banner');
    Route::post(
        'admin/advertisement/updateadvertisement/{id}',
        'AdminadvertisementController@updateadvertisement'
    )->name('update_banner');
    // xu ly disable quang cao
    Route::get(
        'admin/advertisement/disableadvertisement/{id}',
        'AdminadvertisementController@disableadvertisement'
    )->name('disable_banner');
    // xu ly kich hoat lai quang cao
    Route::get(
        'admin/advertisement/restoreadvertisement/{id}',
        'AdminadvertisementController@restoreadvertisement'
    )->name('restore_banner');
    // xu ly xoa quang cao
    Route::get(
        'admin/advertisement/deleteadvertisement/{id}',
        'AdminadvertisementController@deleteadvertisement'
    )->name('delete_banner');
    // Module quyen
    // them quyen
    Route::get('admin/role/addrole', 'AdminRoleController@addrole')->name(
        'addrole'
    );
    // xu ly them quyen
    Route::get(
        'admin/role/storeaddrole',
        'AdminRoleController@storeaddrole'
    )->name('storeaddrole');
    // Them quyen cho user
    Route::get(
        'admin/role/addrolesusers',
        'AdminRoleController@addrolesusers'
    )->name('addrolesusers');
    // Xu ly them quyen cho user
    Route::get(
        'admin/role/addstoreroleuser',
        'AdminRoleController@addstoreroleuser'
    )->name('addstoreroleuser');
    // Xoa quyen cua user
    Route::get(
        'admin/role/deleteroleuser/{id}',
        'AdminRoleController@deleteroleuser'
    )->name('delete_role_user');
    // =======================end module quang cao======================
    // // Tích hơp trình soạn thảo tinycloud
    Route::group(
        ['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']],
        function () {
            \UniSharp\LaravelFilemanager\Lfm::routes();
        }
    );
});
