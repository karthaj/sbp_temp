<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingZoneMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_zone_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shipping_zone_id')->unsigned()->index();
            $table->integer('shipping_method_id')->unsigned()->index();
            $table->string('email');
            $table->string('display_name');
            $table->decimal('min_order',20,2)->default(0.00);
            $table->decimal('rate',20,2)->default(0.00);
            $table->tinyInteger('eligible_type')->comment('0 = per order, 1 = per item')->nullable();
            $table->tinyInteger('restriction_type')->comment('0 = price based, 1 = weight based')->nullable();
            $table->tinyInteger('is_free')->default(0);
            $table->tinyInteger('need_range')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('created_by')->nullable();
            $table->string('browser')->nullable();
            $table->string('platform')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->timestamp('created_at_tz')->nullable();
            $table->timestamp('updated_at_tz')->nullable();
            $table->timestamps();

            $table->foreign('shipping_zone_id', 'sz_id_foreign')->references('id')->on('shipping_zones')->onDelete('cascade');
            $table->foreign('shipping_method_id', 'sm_id_foreign')->references('id')->on('shipping_methods')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_zone_methods');
    }
}
