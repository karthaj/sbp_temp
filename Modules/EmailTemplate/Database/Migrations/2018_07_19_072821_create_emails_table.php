<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id')->unsigned()->index()->nullable();
            $table->string('email_template_name');
            $table->string('slug');
            $table->string('email_subject');
            $table->text('email_body');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('browser')->nullable();
            $table->string('platform')->nullable();
            $table->ipAddress('ip_address')->nullable();
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
        Schema::dropIfExists('emails');
    }
}
