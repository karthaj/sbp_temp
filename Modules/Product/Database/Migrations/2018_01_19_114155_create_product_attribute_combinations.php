<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAttributeCombinations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_attribute_combinations', function (Blueprint $table) {
            $table->integer('product_attribute_id')->unsigned()->index();
            $table->integer('option_id')->unsigned()->index();

            $table->foreign('product_attribute_id', 'pa_id_foreign')->references('id')->on('product_attributes')->onDelete('cascade');
            $table->foreign('option_id')->references('id')->on('options')->onDelete('cascade');
            $table->primary(['product_attribute_id', 'option_id'], 'pa_id_op_id_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_attribute_combinations');
    }
}
