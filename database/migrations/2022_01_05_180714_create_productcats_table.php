<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductcatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productcats', function (Blueprint $table) {
            $table->id();
            $table->string('catname');
            $table->string('slug');
            $table->string('creator', 50)->nullable();
            $table->string('repairer', 50)->nullable();
            $table->string('disabler', 50)->nullable();
            $table->unsignedBigInteger('parentlist_id');
            $table
                ->foreign('parentlist_id')
                ->references('id')
                ->on('parentlists')
                ->onDelete('cascade'); //Thiết lập khóa ngoại cho bảng xóa du lieu ca 2 bang
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productcats');
    }
}
