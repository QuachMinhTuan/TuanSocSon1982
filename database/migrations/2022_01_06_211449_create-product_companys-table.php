<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCompanysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_companys', function (Blueprint $table) {
            $table->id();
            $table->string('namecompany', 50)->nullable();
            $table->string('slug_namecompany');
            $table->string('creator', 50)->nullable();
            $table->string('repairer', 50)->nullable();
            $table->string('disabler', 50)->nullable();
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
        Schema::dropIfExists('product_companys');
    }
}
