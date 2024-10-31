<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference', 32);
            $table->string('order_id');
            $table->integer('store_id')->unsigned()->index();
            $table->integer('customer_id')->unsigned()->index();
            $table->integer('carrier_id')->unsigned()->index()->nullable();
            $table->string('order_source');
            $table->enum('order_source', ['online', 'pos'])->default('online');
            $table->integer('cart_id')->unsigned()->index()->nullable();
            $table->integer('currency_id')->unsigned()->index();
            $table->integer('shipping_address_id')->unsigned()->index();
            $table->integer('billing_address_id')->unsigned()->index();
            $table->integer('current_state')->unsigned()->index();
            $table->integer('payment_method')->unsigned()->index();
            $table->string('shipping_module');
            $table->decimal('total_discounts',20,2)->default(0);
            $table->decimal('total_paid',20,2)->default(0);
            $table->decimal('total_paid_tax_incl',20,2)->default(0);
            $table->decimal('total_paid_tax_excl',20,2)->default(0);
            $table->decimal('total_real_paid',20,2)->default(0);
            $table->decimal('total_products',20,2)->default(0);
            $table->decimal('total_products_wt',20,2)->default(0);
            $table->decimal('total_shipping')->default(0);
            $table->decimal('total_shipping_tax_incl',20,2)->default(0);
            $table->decimal('total_shipping_tax_excl',20,2)->default(0);
            $table->decimal('surcharge',20,2)->default(0);
            $table->decimal('store_credits',20,2)->default(0);
            $table->integer('invoice_number')->nullable();
            $table->dateTime('invoice_date')->nullable();
            $table->dateTimeTz('invoice_date_tz')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamps();

            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('carrier_id')->references('id')->on('shipping_zone_methods')->onDelete('cascade');
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
            $table->foreign('shipping_address_id')->references('id')->on('addresses')->onDelete('cascade');
            $table->foreign('billing_address_id')->references('id')->on('addresses')->onDelete('cascade');
            $table->foreign('current_state')->references('id')->on('order_states')->onDelete('cascade');
            $table->foreign('payment_method')->references('id')->on('store_payments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
