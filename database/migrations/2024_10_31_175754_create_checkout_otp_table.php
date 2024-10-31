<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckoutOtpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('otp', function (Blueprint $table) {
            $table->id(); 
            $table->integer('cart_id'); // Link OTP to cart
            $table->string('otp_code', 5); // Storing OTP code
            $table->timestamp('expires_at'); // Expiry time for OTP
            $table->integer('attempts')->default(0); // Track attempts
            $table->timestamps();
    
            // Foreign keys to link OTP with customer and cart
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checkout_otp');
    }
}
