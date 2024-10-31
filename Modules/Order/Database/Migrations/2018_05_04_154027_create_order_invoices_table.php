<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned()->index();
            $table->integer('number');
            $table->decimal('total_discount',20,6)->default(0);
            $table->decimal('total_paid_tax_excl',20,6)->default(0);
            $table->decimal('total_paid_tax_incl',20,6)->default(0);
            $table->decimal('total_products',20,6)->default(0);
            $table->decimal('total_products_wt',20,6)->default(0);
            $table->decimal('total_shipping_tax_excl',20,6)->default(0);
            $table->decimal('total_shipping_tax_incl',20,6)->default(0);
            $table->dateTime('created_at_tz');
            $table->dateTime('created_at');

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_invoices');
    }
}
