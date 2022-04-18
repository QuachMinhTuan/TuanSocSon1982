<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/solid.min.css">
    <link rel="stylesheet" href="{{ asset('public/css/style.css') }}">
    <title>Admintrator-affilate-maketting</title>
</head>

<body>
    <div id="warpper" class="nav-fixed">
        <nav class="topnav shadow navbar-light bg-white d-flex">
            <div class="navbar-brand"><a href="?">UNITOP ADMIN</a></div>
            <div class="nav-right ">
                <div class="btn-group mr-auto">
                    <button type="button" class="btn dropdown" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">

                        <i class="plus-icon fas fa-plus-circle"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ url('admin/user/add') }}">Thêm thành viên</a>
                        <a class="dropdown-item" href="{{ url('admin/product/cat/addcatparentcatproduct') }}">Thêm
                            danh mục cha</a>
                        <a class="dropdown-item" href="{{ url('admin/product/cat/addcatproduct') }}">Thêm danh mục
                            sản phẩm</a>
                        <a class="dropdown-item" href="{{ url('admin/product/addproduct') }}">Thêm sản phẩm</a>
                        <a class="dropdown-item" href="{{ url('admin/slider/addslider') }}">Thêm slider</a>
                        <a class="dropdown-item" href="{{ url('admin/advertisement/addadvertisement') }}">Thêm quảng
                            cáo</a>
                    </div>
                </div>
                <a href="{{route('index')}}" class="btn btn-primary btn-lg" id="out_pople"
                    class="out_pople">Người dùng</a>
                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        {{-- Phan 24 bai 266 : hien thi user dang nhap --}}
                        {{ Auth::user()->name }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="{{ route('user.edit', Auth::id()) }}">Tài khoản</a>
                        {{-- Phan 24 bai 266 --}}
                        {{-- Paste thang nay vao day moi logout duoc : --}}
                        {{-- He thong laravel bat phai logout qua phuong thuc nay(cua ben app.blade.php): copy ben app.blade.php(layout cua he thong auth cua laravel) --}}
                        <a class="dropdown-item" href="{{ url('logout') }}" onclick="event.preventDefault();
                                      document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                        {{-- Logout xong thi quay tro lai trang login:muon quay tro lai trang nao thi o Auth\LoginController doi thang --}}
                        {{-- use AuthenticatesUsers{logout as perform_logout} --}}
                        {{-- Sau day them 1 phuong thuc ben duoi --}}
                        {{-- public function logout(Request $request) //cau truc 2
                        {
                        $this->performLogout($request);
                        return redirect()->route('login'); //chuyen huong den login
                        // return redirect()->route('/'); //chuyen huong den ban dau xem sao: oke duoc
                        // Chuyen huong ve dau thi minh dat ten ben phan route va sau do chuyen huong nhu binh thuong
                        } --}}

                    </div>
                </div>
            </div>
        </nav>
        <!-- end nav  -->
        {{-- Phan 24 bai 280 : Tao active cho module , xuat thu o day --}}
        @php
            $module_active = session('module_active');
            // dd($module_active);
            // echo $module_active;
        @endphp

        <div id="page-body" class="d-flex">
            <div id="sidebar" class="bg-white">
                <ul id="sidebar-menu">
                    <li class="nav-link {{ $module_active == 'dashboard' ? 'active' : '' }}">
                        <a href="{{ url('dashboard') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Dashboard
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                    </li>
                    <li class="nav-link {{ $module_active == 'slider' ? 'active' : '' }}">
                        <a href="{{ url('admin/slider/addslider') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Slider
                        </a>
                        <i class="arrow fas fa-angle-down"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/slider/addslider') }}">Thêm slider</a></li>
                        </ul>
                    </li>
                    <li class="nav-link {{ $module_active == 'product' ? 'active' : '' }}">
                        <a href="{{ url('admin/product/listproduct') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Sản phẩm
                        </a>
                        <i class="arrow fas fa-angle-down"></i>
                        <ul class="sub-menu">
                            <ul class="sub-menu">
                                <li><a href="{{ url('admin/product/cat/addcatparentcatproduct') }}">Thêm danh mục
                                        cha</a></li>
                                <li><a href="{{ url('admin/product/cat/addcatproduct') }}">Thêm danh mục sản phẩm</a>
                                </li>
                                <li><a href="{{ url('admin/product/addcolorproduct') }}">Thêm màu sản phẩm</a></li>
                                <li><a href="{{ url('admin/product/add_company_product') }}">Thêm hãng sản phẩm</a>
                                </li>
                                <li><a href="{{ url('admin/product/addproduct') }}">Thêm sản phẩm</a></li>
                                <li><a href="{{ url('admin/product/listproduct') }}">Danh sách sản phẩm</a></li>
                            </ul>
                        </ul>
                    </li>
                    <li class="nav-link {{ $module_active == 'user' ? 'active' : '' }}">
                        <a href="{{ url('admin/user/list') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Users
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/user/add') }}">Thêm mới</a></li>
                            <li><a href="{{ url('admin/user/list') }}">Danh sách</a></li>
                        </ul>
                    </li>
                    <li class="nav-link {{ $module_active == 'role' ? 'active' : '' }}">
                        <a href="{{ url('admin/role/addrolesusers') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Quyền
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/role/addrole') }}">Thêm quyền</a></li>
                            <li><a href="{{ url('admin/role/addrolesusers') }}">Thêm quyền cho user</a></li>
                            {{-- <li><a href="{{ url('admin/user/list') }}">Danh sách</a></li> --}}
                        </ul>
                    </li>
                    <li class="nav-link {{ $module_active == 'advertisement' ? 'active' : '' }}">
                        <a href="{{ url('admin/advertisement/addadvertisement') }}">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Quảng cáo
                        </a>
                        <i class="arrow fas fa-angle-down"></i>
                        <ul class="sub-menu">
                            <li><a href="{{ url('admin/advertisement/addadvertisement') }}">
                                    Thêm quảng cáo</a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
            {{-- Tao cai cong de ket noi toi cac trang --}}
            <div id="wp-content">
                {{-- Phan 24 bai 266 --}}
                @yield('content')
            </div>

        </div>

    </div>
    {{-- icon back-to-top --}}
    <div id="back-to-top">
        <i class="fas fa-chevron-up"></i>
    </div>

    {{-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> --}}
    <script src="{{ url('public/js/jquery-2.2.4.min.js') }}"></script>
    {{-- Phan 24 bai 266 --}}
    <script src="{{ url('public/js/app.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
</body>

</html>
