<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDetailTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_detail_taxes', function (Blueprint $table) {
            $table->integer('order_detail_id')->unsigned()->index();
            $table->integer('tax_id')->unsigned()->index();
            $table->decimal('rate',16,2)->default(0);
            $table->decimal('unit_amount',16,2)->default(0);
            $table->decimal('total_amount',16,2)->default(0);

            $table->foreign('order_detail_id')->references('id')->on('order_details')->onDelete('cascade');
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
        Schema::dropIfExists('order_detail_taxes');
    }
}
