<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id')->unsigned()->index()->nullable();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->integer('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('password')->nullable();
            $table->string('avatar')->nullable();
            $table->text('note')->nullable();
            $table->tinyInteger('newsletter')->default(0);
            $table->tinyInteger('is_guest')->default(0);
            $table->tinyInteger('active')->default(0);
            $table->ipAddress('ip_address');
            $table->string('browser');
            $table->string('platform');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
