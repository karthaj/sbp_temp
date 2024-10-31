<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference', 32);
            $table->integer('store_id')->unsigned()->index();
            $table->integer('guest_id')->unsigned()->index()->nullable();
            $table->integer('customer_id')->unsigned()->index()->nullable();
            $table->integer('delivery_option')->unsigned()->index()->nullable();
            $table->integer('delivery_address_id')->unsigned()->index()->nullable();
            $table->integer('invoice_address_id')->unsigned()->index()->nullable();
            $table->integer('currency_id')->unsigned()->index();
            $table->json('checkout_session_data')->nullable();
            $table->timestamp('created_at_tz')->nullable();
            $table->timestamp('updated_at_tz')->nullable();
            $table->timestamps();

            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('guest_id')->references('id')->on('guests')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('delivery_option')->references('id')->on('shipping_zone_methods')->onDelete('cascade');
            $table->foreign('delivery_address_id')->references('id')->on('addresses')->onDelete('cascade');
            $table->foreign('invoice_address_id')->references('id')->on('addresses')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
