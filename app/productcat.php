<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class productcat extends Model
{
    //  Model page
    protected $fillable=['catname','slug','parentlist_id','creator','repairer','disabler'];
    function products(){
       // Lấy tất cả các san pham  do 1 danh mục  tạo ra, bảng product chứa khóa ngoại là productcat_id, Chỉ mục products: nhieu bai viet
       return $this->hasmany('App\product');
   }
}
