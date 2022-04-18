<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request; //Phai khai bao thang nay khong bao loi thu vien request
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    // use AuthenticatesUsers; //de the nay thi khi logout se chuyen ve trang ngoai cug (trang chu cua he thong laravel)
    // use AuthenticatesUsers; goc ban dau cua LoginController->sua lai nhu duoi de Logout ve trang login
    //Phan 24 bai 266 : them //cau truc 1 va phuong thuc 2 de khi logout se chuyen huong ve trang login cua he thong auth chu khong phai theo mac dinh ra ben ngoai
    use AuthenticatesUsers {
        logout as performLogout; //cau truc 1
    }

    //Phan 24 bai 266
    public function logout(Request $request)
    {
        //cau truc 2
        $this->performLogout($request);
        return redirect()->route('login'); //chuyen huong den login
        // return redirect()->route('/'); //chuyen huong den ban dau xem sao: oke duoc
        // Chuyen huong ve dau thi minh dat ten ben phan route va sau do chuyen huong nhu binh thuong
    }
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
