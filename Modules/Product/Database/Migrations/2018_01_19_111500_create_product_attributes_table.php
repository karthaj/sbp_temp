<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned()->index();
            $table->decimal('cost_price',20,6)->default(0.00);
            $table->decimal('selling_price',20,6)->default(0.00);
            $table->decimal('special_price',20,6)->default(0.00);
            $table->dateTime('special_active_on')->nullable();
            $table->dateTime('special_end_on')->nullable();
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->string('isbn', 32)->nullable();
            $table->string('upc', 12)->nullable();
            $table->decimal('width',20,2)->default(0)->nullable();
            $table->decimal('height',20,2)->default(0)->nullable();
            $table->decimal('depth',20,2)->default(0)->nullable();
            $table->decimal('weight',20,2)->default(0);
            $table->tinyInteger('available_for_order')->default(1);  
            $table->tinyInteger('pre_order')->default(0);
            $table->date('available_date')->nullable();  
            $table->timestamp('created_at_tz')->nullable();
            $table->timestamp('updated_at_tz')->nullable();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_attributes');
    }
}
