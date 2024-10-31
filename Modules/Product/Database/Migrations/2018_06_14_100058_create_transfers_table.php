<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id')->unsigned()->index();
            $table->integer('store_location_id')->unsigned()->index()->nullable();
            $table->string('reference',10);
            $table->enum('type', ['consignment', 'SA', 'damage', 'stolen', 'return'])->default('consignment');
            $table->tinyInteger('stock_request');
            $table->string('remarks')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 = pending, 1 = completed, 2 = rejected');
            $table->timestamp('created_at_tz')->nullable();
            $table->timestamp('updated_at_tz')->nullable();
            $table->timestamps();

            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('store')->references('id')->on('store_locations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfers');
    }
}
