<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned()->index();
            $table->integer('order_invoice_id')->unsigned()->index()->nullable();
            $table->integer('store_id')->unsigned()->index();
            $table->integer('product_id')->unsigned()->index();
            $table->integer('product_attribute_id')->unsigned()->index()->nullable();
            $table->string('product_name');
            $table->integer('product_quantity')->default(0);
            $table->integer('product_quantity_refunded')->default(0);
            $table->decimal('product_price',20,2)->default(0);
            $table->string('product_sku')->nullable();
            $table->string('product_barcode')->nullable();
            $table->string('product_isbn',32)->nullable();
            $table->string('product_upc',12)->nullable();
            $table->decimal('product_weight',20,2)->nullable();
            $table->decimal('total_price_tax_incl',20,2)->default(0);
            $table->decimal('total_price_tax_excl',20,2)->default(0);
            $table->decimal('unit_price_tax_incl',20,2)->default(0);
            $table->decimal('unit_price_tax_excl',20,2)->default(0);
            $table->decimal('discount_amount',20,2)->default(0);
            $table->decimal('total_shipping_price_tax_incl',20,2)->default(0);
            $table->decimal('total_shipping_price_tax_excl',20,2)->default(0);
            $table->decimal('surcharge',20,2)->default(0);
            $table->decimal('original_product_price',20,2)->default(0);
            $table->decimal('original_cost_price',20,2)->default(0);

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('order_invoice_id')->references('id')->on('order_invoices')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('product_attribute_id')->references('id')->on('product_attributes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
