<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('public/css/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('public/fonts/css/all.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/lightslider.css') }}">
    {{-- import font awesome truc tiep --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/solid.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- import bootstrap truc tiep -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('public/css/responsive.css') }}">
    <!-- responsive : đặt nó cuối cùng vì css sau sẽ đè lên css trước -->
    <title>Affilate</title>
</head>

<body>
    <style type="text/css">
        #fadeIn {
            border: 1px solid #ccc;
            background-color: #ddd;
            display: none;
        }

    </style>
    @php
        use Illuminate\Support\Str;
        use App\product;
        use App\productcat;
        use App\parentlist;
        use Illuminate\Http\Request;
        use Illuminate\Http\Response; //Phải khai báo thư viện này để dùng cookie
    @endphp
    <div id="site">

        <div id="wrapper">
            <div id="header-bootstrap" class="container-fluid fixed-top ">
                <div class="row no-gutters">
                    <div class="col-md-2 col-sm-2 col-2 py-2 text-center">
                        <a href="{{ route('index') }}" id="Logo"><img src="{{ asset('public/image/logo.png') }}"
                                alt="" class="img-fluid"></a>
                    </div>
                    <div id="header-right" class="col-md-10 col-sm-10 col-10 py-2 text-right">
                        <div class="container-fluid">
                            <div class="row no-gutters">
                                <div
                                    class="col-md-12 col-md-12 col-sm-12 col-12 d-none d-md-block d-lg-block d-xl-block py-2 text-center">
                                    <ul id="main-menu" class="">
                                        <li class="menu-item">
                                            <a href="{{ route('index') }}" class="category-parent">Trang Chủ</a>
                                        </li>
                                        <li class="menu-item">
                                            <a href="{{ route('products') }}" class="category-parent"> Sản Phẩm</a>
                                        </li>
                                        {{-- xuat danh muc cha va danh muc san pham --}}
                                        @foreach ($parentlists as $parent)
                                            @if (count(
    product::where('parentlistproduct_id', '=', $parent->id)->where('disabler', '=', 'active')->get(),
) > 0)

                                                <li id="{{ $parent->id }}" class="menu-item">
                                                    <a href="{{ route('category', $parent->slug) }}"
                                                        class="category-parent">
                                                        {{ Str::of($parent->catparent)->title() }}</a>
                                                    <ul id="{{ 'dataul' . '-' . $parent->id }}"
                                                        class="cateory-product-child">
                                                        @foreach ($productcats as $productcat)
                                                            @if ($productcat->parentlist_id == $parent->id &&
    count(
        product::where('productcat_id', '=', $productcat->id)->where('disabler', '=', 'active')->get(),
    ) > 0)
                                                                <li>
                                                                    <a
                                                                        href="{{ route('categoryproduct', $productcat->slug) }}">{{ Str::of($productcat->catname)->title() }}</a>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @endif

                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col-md-12 col-sm-12 col-12 d-md-none py-2 text-right">
                                    <a href=" " id="icon-menu-bootstrap" class="fas fa-bars text-light"></a>
                                </div>
                            </div>
                            <!-- form search tren 768 -->
                            <div class="row no-gutters d-xl-block d-lg-block d-md-block d-none">
                                <div class="col-md-12">
                                    <div id="search-form" class="text-center py-3">
                                        <form action="{{ route('search') }}" method="GET" class="form-search">
                                            @csrf
                                            <input type="text" class="input-search"
                                                placeholder="Nhập từ khóa tìm kiếm tại đây" name="search">
                                            <button class="submit bg-light">Tìm kiếm</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- form search-responsive<768 -->
                <div class="row d-sm-block d-md-none d-lg-none d-xl-none">
                    <div class="col-md-12">
                        <div id="search-form-responsive" class="text-center py-3">
                            <form action="{{ route('search') }}" method="GET" class="form-search-responsive">
                                @csrf
                                <input type="text" class="input-search-responsive py-1"
                                    placeholder="Nhập từ khóa tìm kiếm tại đây" name="search">
                                <button class="submit-responsive bg-light py-1">Tìm kiếm</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- end header -->
            <!-- w-content -->
            <div id="wp-content-bootstrap" class="container">
                <div class="row no-gutters ">
                    <div id="slidebar-bootstrap" class="col-md-3">
                        <div class="menu-slinebar">
                            <h5 class="title-menu">DANH MỤC SẢN PHẨM</h5>
                            <ul class="category-product">
                                @foreach ($productcats as $productcat)
                                    @if (count(
    product::where('productcat_id', '=', $productcat->id)->where('disabler', '=', 'active')->get(),
) > 0)
                                        <li>
                                            <a href="{{ route('categoryproduct', $productcat->slug) }}">
                                                {{ Str::of($productcat->catname)->title() }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                        <div class="banner">
                            <ul>
                                @foreach ($banners as $banner)
                                    <li class="mb-4 mt-4">
                                        <img src="{{ asset($banner->img_banner) }}" alt="banner"
                                            class="img-fluid">
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                    <div id="content" class="col-md-9 slidebar-left">


                        <div class="container">
                            <div class="row">
                                <div class="col-md-5 text-center img-detail-product detail-thumb">
                                    <img src="{{ asset($product->thumbnail) }} " class="img-fluid"
                                        title="{{ Str::of($product->name)->title() }}" alt="">

                                </div>
                                <div class="col-md-7 product-information">
                                    <h5 class="name-product">Tên sản phẩm : {{ Str::of($product->name)->title() }}
                                    </h5>
                                    <div class="information_product">
                                        {!! $product->product_information !!}
                                    </div>
                                    <p class="price-product">Giá : {{ number_format($product->price, 0, ',', '.') }}đ
                                    </p>
                                    @if ($product->color != '')
                                        <p class="price-product">Màu sắc : {{ $product->color }} </p>
                                    @endif
                                    <a href="{{ $product->shortened_link }}"
                                        class="btn btn-outline-success px-4 py-1 px-4 mt-2 ordered">Đặt mua</a>
                                </div>
                            </div>
                        </div>
                        <div class="container ">
                            <div class="row ">
                                <div class="col-md-12 product-description ">
                                    <h5 class=" ">Mô tả sản phẩm</h5>
                                    {!! $product->description !!}

                                </div>
                            </div>
                        </div>


                        <div class="container test-height">
                            <div class="row">
                                <div class="col-md-12 demo">
                                    <h4>Sản phẩm cùng danh mục</h4>
                                    <div class="mt-3 mb-3 height">
                                        <ul id="content-slider" class="content-slider">
                                            @foreach ($products_the_firm as $product_item)
                                                <li class="card-group">
                                                    <div class="card mb-3">
                                                        <a href="{{ route('detailproduct', $product_item->slug_name) }}"
                                                            class="detail-thumb "
                                                            title="{{ Str::of($product_item->slug_name)->title() }}">
                                                            <img src="{{ asset($product_item->thumbnail) }}" alt=" "
                                                                class="card-img img-fluid "></a>
                                                        <div class="card-body text-center p-3">
                                                            <a href="{{ route('detailproduct', $product_item->slug_name) }}"
                                                                class="card-title mb-1 name-product"
                                                                title="{{ Str::of($product_item->name)->title() }}">{{ Str::of($product_item->name)->title() }}</a>
                                                            <span
                                                                class="ordered_buy">{{ number_format($product_item->price, 0, ',', '.') }}đ</span>
                                                            <a href="{{ $product_item->shortened_link }}"
                                                                class="btn btn-outline-success btn-sm mt-2 px-3 ordered">Đặt
                                                                mua</a>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @isset($uniques)
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>Những sản phẩm đã xem</h4>
                                    <div class="mt-3 mb-3">
                                        <ul id="" class="list-unstyled list-inline">
                                            @foreach ($uniques as $item)
                                                <li class="col-md-3 float-left card-group p-1">
                                                    <div class="card mb-3">
                                                        <a href="{{ route('detailproduct', $item->slug_name) }}"
                                                            class="detail-thumb "
                                                            title="{{ Str::of($item->slug_name)->title() }}">
                                                            <img src="{{ asset($item->thumbnail) }}" alt="image "
                                                                class="card-img img-fluid "></a>
                                                        <div class="card-body text-center p-3">
                                                            <a href="{{ route('detailproduct', $item->slug_name) }}"
                                                                class="card-title mb-1 name-product"
                                                                title="{{ Str::of($item->name)->title() }}">{{ Str::of($item->name)->title() }}</a>
                                                            <span
                                                                class="ordered_buy">{{ number_format($item->price, 0, ',', '.') }}đ</span>
                                                            <a href="{{ $item->shortened_link }}"
                                                                class="btn btn-outline-success btn-sm mt-2 px-3 ordered">Đặt
                                                                mua</a>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @endisset

                    </div>
                </div>
            </div>
            <!-- footter -->
            <div id="footer-bootstrap">
                <div class="container py-3">
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-4">
                            <h5 class="text-light">Shoppe</h5>
                            <ul class="list-footer">
                                <li class="text-light">
                                    <a href="https://shopee.vn/ "
                                        class="producer text-decoration-none text-light">Phương thức thanh toán</a>
                                </li>
                                <li class="text-light">
                                    <a href="https://shopee.vn/ " class="producer text-decoration-none text-light">Chính
                                        sách bảo hành</a>
                                </li>
                                <li class="text-light">
                                    <a href="https://shopee.vn/ " class="producer text-decoration-none text-light">Giới
                                        thiệu</a>
                                </li>
                                <li class="text-light">
                                    <a href="https://shopee.vn/ " class="producer text-decoration-none text-light">Liên
                                        hệ</a>
                                </li>
                            </ul>

                        </div>
                        <div class="col-md-4 col-sm-4 col-4 ">
                            <h5 class="text-light">Tiki</h5>
                            <ul class="list-footer ">
                                <li class="text-light">
                                    <a href="https://tiki.vn/ " class="producer text-decoration-none text-light">Phương
                                        thức thanh toán</a>
                                </li>
                                <li class="text-light">
                                    <a href="https://tiki.vn/ " class="producer text-decoration-none text-light">Chính
                                        sách bảo hành</a>
                                </li>
                                <li class="text-light">
                                    <a href="https://tiki.vn/ " class="producer text-decoration-none text-light">Giới
                                        thiệu</a>
                                </li>
                                <li class="text-light">
                                    <a href="https://tiki.vn/ " class="producer text-decoration-none text-light">Liên
                                        hệ</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-4 col-sm-4 col-4 ">
                            <h5 class="text-light">Lazada</h5>
                            <ul class="list-footer ">
                                <li class="text-light">
                                    <a href="https://www.lazada.vn/ "
                                        class="producer text-decoration-none text-light">Phương thức thanh toán</a>
                                </li>
                                <li class="text-light">
                                    <a href="https://www.lazada.vn/ "
                                        class="producer text-decoration-none text-light">Chính sách bảo hành</a>
                                </li>
                                <li class="text-light">
                                    <a href="https://www.lazada.vn/ "
                                        class="producer text-decoration-none text-light">Giới thiệu</a>
                                </li>
                                <li class="text-light">
                                    <a href="https://www.lazada.vn/ "
                                        class="producer text-decoration-none text-light">Liên hệ</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- Nut back to top -->
                    <div id="back-to-top">
                        <i class="fas fa-chevron-up"></i>
                    </div>
                </div>
            </div>
            <!-- end footer -->

        </div>
        <!-- end wrapper -->
        <div id="wp-respon-menu">
            <div id="respon-head">
                <h5>Danh mục</h5>
                <a href=" " id="icon-menu" class="fas fa-bars"></a>
            </div>

            <ul id="responsive-main-menu" class=" ">
                <li class="respon-menu-item">
                    <a href="{{ route('index') }}" title="Trang-Chu">Trang Chủ</a>
                </li>
                <li class="respon-menu-item">
                    <a href="{{ route('products') }}" title="San-Pham"> Sản Phẩm</a>
                </li>
                @foreach ($parentlists as $parent)
                    @if (count(
    product::where('parentlistproduct_id', '=', $parent->id)->where('disabler', '=', 'active')->get(),
) > 0)
                        <li class="respon-menu-item">
                            <a href="{{ route('category', $parent->slug) }}"
                                title="{{ Str::of($parent->slug)->title() }}" class="respon-category">
                                {{ Str::of($parent->catparent)->title() }}</a>
                            <i class="fas fa-angle-down icon-dropdown" id="{{ $parent->id }}"></i>
                            <ul id="respon-data-{{ $parent->id }}" class="menu-sub">
                                @foreach ($productcats as $productcat)
                                    @if ($productcat->parentlist_id == $parent->id &&
    count(
        product::where('productcat_id', '=', $productcat->id)->where('disabler', '=', 'active')->get(),
    ) > 0)
                                        <li>
                                            <a href="{{ route('categoryproduct', $productcat->slug) }}"
                                                title="{{ Str::of($productcat->catname)->title() }}">{{ Str::of($productcat->catname)->title() }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @endif

                @endforeach
            </ul>

        </div>

    </div>
    <!-- end site -->

    <script src="{{ asset('public/js/jquery.js') }}"></script>

    <script type="text/javascript" src="{{ asset('public/js/main.js ') }}"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>

    {{-- Tich hop slider lightslider-master(plugin) --}}
    {{-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> --}}
    <script src="{{ asset('public/js/lightslider.js') }}"></script>
    {{-- <script>
        $(document).ready(function() {
            $("#content-slider").lightSlider({
                loop: true,
                keyPress: true,
                speed: 500,
                auto: true,
                item: 4,
            });
        });
    </script> --}}
</body>

</html>
