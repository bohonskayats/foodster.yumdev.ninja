<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable()->default(NULL);
            $table->integer('user_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->string('street')->nullable()->default(NULL);
            $table->string('apartment')->nullable()->default(NULL);
            $table->string('intercom')->nullable()->default(NULL);
            $table->string('floor')->nullable()->default(NULL);


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
        Schema::dropIfExists('addresses');
    }
}
