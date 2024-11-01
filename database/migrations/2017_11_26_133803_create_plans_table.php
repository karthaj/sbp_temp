<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('monthly', 10, 2)->default(0);
            $table->decimal('yearly', 10, 2)->default(0);
            $table->decimal('quarterly', 10, 2)->default(0);
            $table->decimal('half_monthly', 10, 2)->default(0);
            $table->integer('products_limit')->default(0);
            $table->integer('accounts_limit')->default(0);
            $table->tinyInteger('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
