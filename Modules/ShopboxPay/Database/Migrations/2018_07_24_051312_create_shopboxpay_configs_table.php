<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopboxpayConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopboxpay_configs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('payment_id')->unsigned()->index();
            $table->integer('plugin_id')->unsigned()->index();
            $table->string('display_name');
            $table->string('alias');
            $table->decimal('tdr_rate', 3,2);
            $table->tinyInteger('live')->default(0);
            $table->tinyInteger('active')->default(1);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('browser')->nullable();
            $table->string('platform')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->timestamp('created_at_tz')->nullable();
            $table->timestamp('updated_at_tz')->nullable();
            $table->timestamps();

            $table->foreign('payment_id')->references('id')->on('store_payments')->onDelete('cascade');
            $table->foreign('plugin_id')->references('id')->on('plugins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shopboxpay_configs');
    }
}
