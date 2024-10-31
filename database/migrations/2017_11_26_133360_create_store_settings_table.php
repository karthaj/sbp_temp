<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_settings', function (Blueprint $table) {
            $table->integer('store_id')->unsigned()->index();
            $table->integer('timezone_id')->unsigned()->nullable();
            $table->integer('store_currency')->unsigned()->nullable();
            $table->integer('weight_unit_id')->unsigned()->nullable();
            $table->string('order_id_prefix')->default('#');
            $table->string('order_id_suffix')->nullable();
            $table->integer('store_locations')->default(1);
            $table->text('google_tag_manager')->nullable();
            $table->text('google_analytics')->nullable();
            $table->text('facebook_pixel_id')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('password_hash');
            $table->string('password');
            $table->text('message');
            $table->tinyInteger('enable_password')->default(1)->comment('0 = disabled, 1 = enabled');
            $table->tinyInteger('enable_returns')->default(0)->comment('0 = disabled, 1 = enabled');
            $table->tinyInteger('enable_partial_returns')->default(0)->comment('0 = disabled, 1 = enabled');
            $table->tinyInteger('enable_store_pickup')->default(0)->comment('0 = disabled, 1 = enabled');
            $table->tinyInteger('show_branding')->default(1)->comment('0 = disabled, 1 = enabled');
            $table->integer('reservation_time')->nullable();
            $table->timestamps();

            $table->primary('store_id');
            $table->foreign('store_id')->references('id')->on('stores');
            $table->foreign('timezone_id')->references('id')->on('timezones');
            $table->foreign('store_currency')->references('id')->on('currencies');
            $table->foreign('weight_unit_id')->references('id')->on('weight_units');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_settings');
    }
}
