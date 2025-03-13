<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('shoppers', function (Blueprint $table) {
            $table->id();
            $table->string('shopper_id')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('status')->default('pending'); // pending, paid, failed
            $table->string('payment_link')->nullable(); // Store payment link
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shoppers');
    }
};
