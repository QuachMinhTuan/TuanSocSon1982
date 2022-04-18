<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail; //Phai them thang nay de thuc hien xac thuc tai khoan
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Phan 24 bai 274 : khai bao softDeletes de xoa tam thoi
use Illuminate\Database\Eloquent\SoftDeletes;
class User extends Authenticatable implements MustVerifyEmail //implements MustVerifyEmail copy o phan Model Preparation cua trang chu laravel.com/Security/Email Verification/Model Preparation
{
    use Notifiable;
    // Phan 24 bai 274 : khai bao softDeletes de xoa tam thoi
    use softDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
