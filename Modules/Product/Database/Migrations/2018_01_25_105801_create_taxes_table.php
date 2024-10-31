<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id')->unsigned()->index();
            $table->integer('tax_zone_id')->unsigned()->index();
            $table->string('name');
            $table->tinyInteger('priority');
            $table->tinyInteger('status')->default(1)->comment('1 = active, 2 = inactive');
            $table->timestamp('created_at_tz')->nullable();
            $table->timestamp('updated_at_tz')->nullable();
            $table->timestamps();
            

            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('tax_zone_id')->references('id')->on('tax_zones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxes');
    }
}
