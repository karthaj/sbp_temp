<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImageDimensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_dimensions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('width');
            $table->integer('height');
            $table->tinyInteger('products')->comment('0 = false, 1 = true');
            $table->tinyInteger('categories')->comment('0 = false, 1 = true');
            $table->tinyInteger('suppliers')->comment('0 = false, 1 = true');
            $table->tinyInteger('stores')->comment('0 = false, 1 = true');
            $table->tinyInteger('pattern')->comment('0 = false, 1 = true');
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
        Schema::dropIfExists('image_dimensions');
    }
}
