<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorePluginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_plugins', function (Blueprint $table) {
            $table->integer('plugin_id')->unsigned()->index();
            $table->integer('store_id')->unsigned()->index();
            $table->tinyInteger('active')->default(1);
            $table->tinyInteger('blocked')->default(0);
            
            $table->primary(['plugin_id', 'store_id']);
            $table->foreign('plugin_id')->references('id')->on('plugins')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_plugins');
    }
}
