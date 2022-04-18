<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParentlistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parentlists', function (Blueprint $table) {
            $table->id();
            $table->string('catparent');
            $table->string('slug');
            $table->string('creator',50)->nullable();
            $table->string('repairer',50)->nullable();
            $table->string('disabler',50)->nullable();
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
        Schema::dropIfExists('parentlists');
    }
}
