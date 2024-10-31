<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThemeIndustriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('theme_industries', function (Blueprint $table) {
            $table->integer('theme_id')->unsigned()->index();
            $table->integer('industry_id')->unsigned()->index();

            $table->primary(['theme_id', 'industry_id']);
            $table->foreign('theme_id')->references('id')->on('themes')->onDelete('cascade');
            $table->foreign('industry_id')->references('id')->on('industries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('theme_industries');
    }
}
