<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned()->index();
            $table->string('trans_currency', 3);
            $table->decimal('trans_amount', 20,2);
            $table->string('order_currency', 3);
            $table->decimal('order_amount', 20,2);
            $table->decimal('exchange_rate', 20,2)->default(0);
            $table->integer('payment_method')->unsigned()->index();
            $table->integer('payment_gateway')->unsigned()->index()->nullable();
            $table->integer('refund', 20, 2)->default(0);
            $table->string('transaction_id')->nullable();
            $table->string('card_number')->nullable();
            $table->string('card_brand')->nullable();
            $table->char('card_expiration',7)->nullable();
            $table->string('card_holder')->nullable();
            $table->dateTime('created_at_tz');
            $table->dateTime('created_at');

             $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
             $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
             $table->foreign('payment_method')->references('id')->on('store_payments')->onDelete('cascade');
             $table->foreign('payment_gateway')->references('id')->on('shopboxpay_configs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_payments');
    }
}
