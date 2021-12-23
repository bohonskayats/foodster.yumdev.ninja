<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDishParameterOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dish_parameter_orders', function (Blueprint $table) {
            $table->id();
            //----
            $table->integer('dish_order_id');
            $table->integer('count') ;
            $table->decimal('total_price', $precision = 8, $scale = 2);

            //----
            $table->string('title');
            $table->string('units');
            $table->decimal('price', $precision = 8, $scale = 2);
           // $table->integer('order');
            $table->integer('value');

            //----
            
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dish_parameter_orders');
    }
}
