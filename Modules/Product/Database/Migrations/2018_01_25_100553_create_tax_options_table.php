<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id')->unsigned()->index();
            $table->integer('shipping_tax')->unsigned()->index();
            $table->string('tax_label');
            $table->tinyInteger('charge_tax')->default(1)->comment('0 = before discount, 1 = after discount');
            $table->tinyInteger('price_includes_tax')->default(0)->comment('0 = no, 1 = yes');
            $table->enum('tax_based_on', ['shipping', 'billing', 'store'])->default('shipping');
            $table->tinyInteger('tax_display_product_listing')->default(0)->comment('0 = excluding, 1 = including');
            $table->tinyInteger('tax_display_product_page')->default(0)->comment('0 = excluding, 1 = including');
            $table->tinyInteger('tax_display_cart')->default(0)->comment('0 = excluding, 1 = including');
            $table->tinyInteger('tax_display_order_invoice')->default(0)->comment('0 = excluding, 1 = including');
            $table->tinyInteger('display_tax_charge_cart')->default(1)->comment('1 = As one summarized line item, 2 = Broken down by tax rate');
            $table->tinyInteger('display_tax_charge_order')->default(1)->comment('1 = As one summarized line item, 2 = Broken down by tax rate');
            $table->timestamp('created_at_tz')->nullable();
            $table->timestamp('updated_at_tz')->nullable();
            $table->timestamps();

            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('shipping_tax')->references('id')->on('tax_classes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tax_options');
    }
}
