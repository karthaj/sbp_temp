<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderCarriersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_carriers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned()->index();
            $table->integer('carrier_id')->unsigned()->index();
            $table->integer('shipping_module_id')->unsigned()->index()->nullable();
            $table->integer('order_invoice_id')->unsigned()->index()->nullable();
            $table->decimal('weight',20,2)->nullable();
            $table->decimal('shipping_cost_tax_excl',20,6)->nullable();
            $table->decimal('shipping_cost_tax_incl',20,6)->nullable();
            $table->string('tracking_number',64)->nullable();
            $table->dateTime('created_at_tz');
            $table->dateTime('updated_at_tz');
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('carrier_id')->references('id')->on('shipping_zone_methods')->onDelete('cascade');
            $table->foreign('shipping_module_id')->references('id')->on('plugins')->onDelete('cascade');
            $table->foreign('order_invoice_id')->references('id')->on('order_invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_carriers');
    }
}
