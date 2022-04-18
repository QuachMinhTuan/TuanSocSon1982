// jQuery.noConflict();
jQuery(document).ready(function() {

    // reponsive thong thuong
    $("#icon-menu").click(function() {
        var $j = jQuery.noConflict();
        $('#site').toggleClass('open-respon-menu');
        $('#icon-menu').toggleClass('far fa-times');
        if ($('.icon-dropdown').hasClass('fas fa-angle-up')) {
            $('.icon-dropdown').removeClass('fas fa-angle-up');
            $('.icon-dropdown').addClass('fas fa-angle-down');
            $('.menu-sub').css('display', 'none');

        }
        return false;
    });
    // $(window).resize(function() {
    //     var $j = jQuery.noConflict();
    //     if ($(document).width() >= 768) {
    //         $('#site').removeClass('open-respon-menu');
    //         $('#icon-menu').removeClass('far fa-times');
    //     };
    //     return false;
    // });
    // Dung bootstrap
    $("#icon-menu-bootstrap").click(function() {
        var $j = jQuery.noConflict();
        $('#site').toggleClass('open-respon-menu');
        $('#icon-menu').addClass('far fa-times');
        return false;
    });
    $(window).resize(function() {
        var $j = jQuery.noConflict();
        if ($(document).width() >= 768) {
            $('#site').removeClass('open-respon-menu');
        };
        if ($('#site').hasClass('open-respon-menu')) {
            $('#site').removeClass('open-respon-menu');
            $('#icon-menu').toggleClass('far fa-times');
        }
        // menu con
        if ($('.icon-dropdown').hasClass('fas fa-angle-up')) {
            $('.icon-dropdown').removeClass('fas fa-angle-up');
            $('.icon-dropdown').addClass('fas fa-angle-down');
            $('.menu-sub').css('display', 'none');

        }
        return false;
    });
    $(window).scroll(function() {
        var $j = jQuery.noConflict();
        $('#site').removeClass('open-respon-menu');
        $('#icon-menu').removeClass('far fa-times');
    });

    // Khi khong resize trinh duyet
    var outer_height_header_bootstrap = $('#header-bootstrap').outerHeight();
    var margin_top_wp_content_bootstrap = outer_height_header_bootstrap + 20 + 'px';
    // console.log(outer_height_header_bootstrap);
    $('#wp-content-bootstrap').css('margin-top', margin_top_wp_content_bootstrap);

    // Thiet lap khoang cach tu wp - content toi dau trang wed khi resize trinh duyet
    $(window).resize(function() {
        var $j = jQuery.noConflict();
        var outer_height_header_bootstrap = $('#header-bootstrap').outerHeight();
        // console.log(outer_height_header_bootstrap);
        var margin_top_wp_content_bootstrap = outer_height_header_bootstrap + 20 + 'px';
        // alert('margin-top-wp-content-bootstrap');
        // 2.Thiết lập khoang cach tu wp-content-bootstrap toi top cua wedsite de tranh header che mat phan content khi responsive
        $('#wp-content-bootstrap').css('margin-top', margin_top_wp_content_bootstrap);
    });
    // Thiet lap chieu rong cho input search khi resize 
    $(window).resize(function() {
        var $j = jQuery.noConflict();
        var window_width = $(window).width();
        var width_header_right = $('#header-right').outerWidth();
        // form responsive khi KT<768 SE HIEN RA
        if (window_width <= 768) {
            $('.form-search-responsive').css('flex-wrap', 'wrap');
            $('.input-search-responsive').css('width', 0.9 * window_width);
            $('.submit-responsive').css('width', 0.3 * window_width);
            $('.submit-responsive').css('margin-top', '10px');
        }

    });

    // hover vao menu top Khi man hinh >768 thi hover: 
    $('.menu-item').hover(function() {
        // var width_menu = $('.cateory-product-child li a');
        // console.log(width_menu);
        // var arr_width = [];
        // width_menu.each(function() {
        //     arr_width.push($(this).text().length);
        // });
        // var maxwidth_menu = Math.max.apply(Math, arr_width);
        // // console.log(width_menu);
        // $('ul.cateory-product-child').css('width', maxwidth_menu + 200 + 'px');
        var $j = jQuery.noConflict();
        var id = $(this).attr('id');
        // alert(id);
        $('#dataul-' + id).stop().slideToggle();
    });

    $(window).resize(function() {
        var $j = jQuery.noConflict();
        // menu con
        if ($('.icon-dropdown').hasClass('fas fa-angle-up')) {
            $('.icon-dropdown').removeClass('fas fa-angle-up');
            $('.icon-dropdown').addClass('fas fa-angle-down');
            $('ul .menu-sub').slideUp();
        }
        return false;
    });

    $(window).scroll(function() {
        var $j = jQuery.noConflict();
        // menu con
        if ($('.icon-dropdown').hasClass('fas fa-angle-up')) {
            $('.icon-dropdown').removeClass('fas fa-angle-up');
            $('.icon-dropdown').addClass('fas fa-angle-down');
            $('ul .menu-sub').slideUp();
        }
        return false;
    });
    // hover vao menu respon Khi man hinh >768 thi hover: 
    $('.icon-dropdown').click(function() {
        var $j = jQuery.noConflict();
        var id = $(this).attr('id');
        // alert(id);
        $(this).toggleClass('fas fa-angle-down fas fa-angle-up');
        $('#respon-data-' + id).stop().slideToggle();
    });

    // ki hover vao button cua carousel co hnh ban tay
    $('.hovercarasel').hover(function() {
        var $j = jQuery.noConflict();
        $('.hovercarasel').css('cursor', 'pointer');
    });


    // tao nut back to top 
    $(window).scroll(function() {
        var $j = jQuery.noConflict();
        if ($(this).scrollTop()) {
            $("#back-to-top").fadeIn();
        } else {
            $("#back-to-top").fadeOut();
        };
    });
    // Nguoi dung bam vao nut bam vao back-to-top thi len dau trang
    $("#back-to-top").click(function() {
        var $j = jQuery.noConflict();
        $('html,body').animate({ scrollTop: 0 }, 1000);
        // {scrollTop :0}:vi tri 0(len tren dau trang)
        // Thoi gian scroll:1s
    });

    // console.log(typeof(lightSlider))
    // kich hoat carousel
    $('#home-slide').carousel({
        // Để kích hoạt sư dụng các option
        interval: 3000,
        ride: "carousel",
    })

});
jQuery(document).ready(function() {
    // Tich hop slider lightslider - master(plugin)
    $("#content-slider").lightSlider({
        loop: true,
        keyPress: true,
        speed: 500,
        auto: true,
        // item: 3,
        responsive: [{
                breakpoint: 1200,
                settings: {
                    item: 3,
                    slideMove: 1,
                    slideMargin: 1,
                }
            },
            {
                breakpoint: 800,
                settings: {
                    item: 2,
                    slideMove: 1
                }
            },
            {
                breakpoint: 480,
                settings: {
                    item: 1,
                    slideMove: 1
                }
            }
        ]
    });
    // thiet lap chieu cao cho khoi san pham cung danh muc
    var ul_li_card = $('#content-slider li');
    var arr_width_card_group = [];
    // console.log($(this).text());
    ul_li_card.each(function() {
        arr_width_card_group.push($(this).outerHeight());
    });
    var maxInNumbers = Math.max.apply(Math, arr_width_card_group);
    ul_li_card.each(function() {
        $(this).css('height', maxInNumbers);
    });
    // var height_demo = maxInNumbers + 300;
    // console.log(maxInNumbers);
    // $('.demo').css('height', height_demo);
    // $('.lSSlideWrapper').css('height', height_demo);
    // $('#content-slider').css('display', 'inline-block');
    // $('#content-slider').css('height', height_demo);
    // $('#content-slider').css('margin-bottom', '50px');
    // $('#test_ul').css('height', height_demo);
    // console.log(height_demo);
    // console.log($('#content-slider').outerHeight());
})