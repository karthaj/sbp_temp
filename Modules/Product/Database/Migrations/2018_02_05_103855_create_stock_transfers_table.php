<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id')->unsigned()->index();
            $table->integer('entity')->unsigned()->index()->nullable();
            $table->integer('user_id')->unsigned()->index()->nullable();
            $table->integer('stock_id')->unsigned()->index();
            $table->integer('order_id')->unsigned()->index()->nullable();
            $table->integer('stock_transfer_reason_id')->unsigned()->index();
            $table->string('user_firstname')->nullable();
            $table->string('user_lastname')->nullable();
            $table->integer('quantity');
            $table->integer('available_quantity');
            $table->smallInteger('sign')->default(1);
            $table->ipAddress('ip_address');
            $table->string('browser');
            $table->string('platform');
            $table->timestamp('created_at_tz')->nullable();
            $table->timestamp('updated_at_tz')->nullable();
            $table->timestamps();

            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('entity')->references('id')->on('store_locations')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('stock_id')->references('id')->on('stocks')->onDelete('cascade');
            $table->foreign('stock_transfer_reason_id')->references('id')->on('stock_transfer_reasons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_transfers');
    }
}
