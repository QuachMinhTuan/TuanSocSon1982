<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Dua timezome vao day de lay thoi gian cac hoat dong theo gio viet nam chu khong phai khai bao rieng le o cac module hay cac action khac nhau
        
        date_default_timezone_set("Asia/Ho_Chi_Minh");//Lấy thời gian theo timezone là HCM
    }
}
