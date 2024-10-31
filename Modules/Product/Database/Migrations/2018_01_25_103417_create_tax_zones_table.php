<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_zones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id')->unsigned()->index();
            $table->string('name');
            $table->enum('type', ['country', 'state', 'zip', 'global'])->default('country');
            $table->tinyInteger('status')->default(1)->comment('0 = inactive, 1 = active');
            $table->timestamp('created_at_tz')->nullable();
            $table->timestamp('updated_at_tz')->nullable();
            $table->timestamp('deleted_at_tz')->nullable();
            $table->softDeletes();
            $table->timestamps();

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
        Schema::dropIfExists('tax_zones');
    }
}
