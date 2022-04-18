$(document).ready(function() {
    // Tao icon backtop
    $(window).scroll(function() {
        if ($(this).scrollTop()) {
            $("#back-to-top").fadeIn();
        } else {
            $("#back-to-top").fadeOut();
        };
    });
    // Nguoi dung bam vao nut bam vao back-to-top thi len dau trang
    $("#back-to-top").click(function() {
        $('html,body').animate({ scrollTop: 0 }, 800);
        // {scrollTop :0}:vi tri 0(len tren dau trang)
        // Thoi gian scroll:1s
    });

    // Cua phan admin cua unitop
    $('.nav-link.active .sub-menu').slideDown();
    // $("p").slideUp();

    $('#sidebar-menu .arrow').click(function() {
        $(this).parents('li').children('.sub-menu').slideToggle();
        $(this).toggleClass('fa-angle-right fa-angle-down');
    });

    $("input[name='checkall']").click(function() {
        var checked = $(this).is(':checked');
        // alert(checked);
        // $('.table-checkall tbody tr td input:checkbox').prop('checked', checked);
        $('input:checkbox').prop('checked', checked);
    });
    // Tuan bo sung them
    // $(".test_checked").click(function() {
    //     var list_checked = $('.test_checked');
    //     var list_check_all=[];
    //     // alert(list_checked);
    //     // $('.table-checkall tbody tr td input:checkbox').prop('checked', checked);
    //     list_checked.each(function(){
    //         if($(this).prop('checked')==true){
    //             list_check_all.push($(this).prop('checked'));
    //         }
    //     });
    //     console.log(list_check_all.length);
    //     if(list_check_all.length>0){
    //         $("input[name='checkall']").prop('checked', 'checked');
    //     }else{
    //         $("input[name='checkall']").prop('checked', '');
    //     }
    // });

});

