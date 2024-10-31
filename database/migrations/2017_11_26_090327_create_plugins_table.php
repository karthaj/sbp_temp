<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePluginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plugins', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->unsigned()->nullable();
            $table->string('plugin_name');
            $table->string('alias');
            $table->string('slug')->unique();
            $table->string('author');
            $table->string('email');
            $table->text('description')->nullable();
            $table->float('price', 8, 2)->default(0.00);
            $table->integer('is_core')->default(1);
            $table->integer('type')->unsigned()->index()->nullable();
            $table->string('version')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0 = disabled, 1 = active');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('type')->references('id')->on('plugin_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plugins');
    }
}
