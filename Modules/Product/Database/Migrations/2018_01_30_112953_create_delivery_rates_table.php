<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shipping_zone_method_id')->unsigned()->index();
            $table->decimal('delimiter1',20,6);
            $table->decimal('delimiter2',20,6);
            $table->decimal('price', 20,6);
            $table->timestamp('created_at_tz')->nullable();
            $table->timestamp('updated_at_tz')->nullable();
            $table->timestamps();

            $table->foreign('shipping_zone_method_id', 'szm_id_foreign')->references('id')->on('shipping_zone_methods')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery_rates');
    }
}
