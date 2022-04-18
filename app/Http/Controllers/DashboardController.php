<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Tao phuong thuc khoi tao construct
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            // Su dung middleware toi uu cho active module_active
            //  Su dung middleware de rang buoc cai session khi di vao moi module, sesion se duoc thay doi theo thiet lap o moi module
            // Neu khong co middleware thi session luon lay cai dau tien la dashbord->khong dung yeu cau bai toan dat ra ->HAY
            Session(['module_active' => 'dashboard']);
            return $next($request);
        });
    }
    function show(Request $request)
    {
        return view('admin.dashboard');
    }
}
