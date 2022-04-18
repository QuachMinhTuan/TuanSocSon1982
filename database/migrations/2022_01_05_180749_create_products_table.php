<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('masp');
            $table->string('shortened_link', 2000);
            $table->string('thumbnail');
            $table->string('name');
            $table->string('slug_name');
            $table->string('color')->nullable();
            $table->unsignedBigInteger('price');
            $table->text('product_information', 2000)->nullable();
            $table->text('description', 2000)->nullable();
            $table->string('the_firm', 50)->nullable();
            $table->string('slug_the_firm')->nullable();
            $table->string('creator', 50)->nullable();
            $table->string('repairer', 50)->nullable();
            $table->string('disabler', 50)->nullable();
            $table->unsignedBigInteger('productcat_id');
            $table
                ->foreign('productcat_id')
                ->references('id')
                ->on('productcats')
                ->onDelete('cascade'); //Thiết lập khóa ngoại cho bảng xóa du lieu ca 2 bang
            $table->unsignedBigInteger('parentlistproduct_id');
            $table
                ->foreign('parentlistproduct_id')
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
        Schema::dropIfExists('products');
    }
}
