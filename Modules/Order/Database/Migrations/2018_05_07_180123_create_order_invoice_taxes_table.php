<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderInvoiceTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_invoice_taxes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_invoice_id')->unsigned()->index();
            $table->integer('tax_id')->unsigned()->index();
            $table->decimal('rate', 16,2)->default(0);
            $table->decimal('amount', 16,2)->default(0);

            $table->foreign('order_invoice_id')->references('id')->on('order_invoices')->onDelete('cascade');
            $table->foreign('tax_id')->references('id')->on('taxes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_invoice_taxes');
    }
}
