<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailTemplateCustomizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_template_customizations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id')->unsigned()->index()->nullable();
            $table->string('logo')->nullable();
            $table->smallInteger('logo_width')->default(180);
            $table->string('accent_color')->default('blue');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('browser');
            $table->string('platform');
            $table->ipAddress('ip_address');
            $table->timestamp('created_at_tz')->nullable();
            $table->timestamp('updated_at_tz')->nullable();
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
        Schema::dropIfExists('email_template_customizations');
    }
}
