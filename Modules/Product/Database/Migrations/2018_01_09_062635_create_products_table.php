<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id')->unsigned()->index();
            $table->integer('brand_id')->unsigned()->nullable();
            $table->integer('tax_class_id')->unsigned()->nullable();
            $table->integer('option_set_id')->unsigned()->nullable();
            $table->integer('shipping_class_id')->unsigned()->nullable();
            $table->string('name')->index();
            $table->string('slug');
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->decimal('cost_price',20,2)->default(0.00)->nullable();
            $table->decimal('selling_price',20,2)->default(0.00);
            $table->decimal('special_price',20,2)->nullable();
            $table->dateTime('special_active_on')->nullable();
            $table->dateTime('special_end_on')->nullable();
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->string('isbn', 32)->nullable();
            $table->string('upc', 12)->nullable();
            $table->integer('minimal_quantity')->nullable();
            $table->decimal('width',20,2)->default(0)->nullable();
            $table->decimal('height',20,2)->default(0)->nullable();
            $table->decimal('depth',20,2)->default(0)->nullable();
            $table->decimal('weight',20,2)->default(0);
            $table->integer('out_of_stock')->default(2);
            $table->tinyInteger('customizable')->default(0);
            $table->tinyInteger('uploadable_files')->default(0);
            $table->tinyInteger('text_fields')->default(0);
            $table->tinyInteger('online')->default(1);
            $table->enum('condition', ['new', 'used', 'refurbished'])->default('new');
            $table->enum('type', ['standard', 'variation', 'virtual'])->default('standard');
            $table->string('tags')->nullable();
            $table->tinyInteger('on_sale')->default(0); 
            $table->tinyInteger('show_condition')->default(0); 
            $table->tinyInteger('available_for_order')->default(0);  
            $table->tinyInteger('pre_order')->default(0)->nullable();
            $table->date('available_date')->nullable();  
            $table->string('available_now')->default('In stock')->nullable();
            $table->string('available_later')->default('Out of stock')->nullable();    
            $table->dateTime('publish_on')->nullable();
            $table->tinyInteger('active')->default(0);
            $table->tinyInteger('state')->default(0);
            $table->tinyInteger('blocked')->default(0);
            $table->timestamp('created_at_tz')->nullable();
            $table->timestamp('updated_at_tz')->nullable();
            $table->timestamp('deleted_at_tz')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->foreign('tax_class_id')->references('id')->on('tax_classes')->onDelete('cascade');
            $table->foreign('shipping_class_id')->references('id')->on('shipping_classes')->onDelete('cascade');
            $table->foreign('option_set_id')->references('id')->on('option_sets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
