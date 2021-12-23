<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDishOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dish_orders', function (Blueprint $table) {
			$table->id();
            $table->decimal('total_price', $precision = 8, $scale = 2);
            $table->text('parameter_description');
            $table->integer('count') ;
            $table->integer('dish_id') ;
            $table->integer('order_id');
            //---
            $table->string('title');
            $table->text('description');
            $table->decimal('base_price', $precision = 8, $scale = 2);
            //---           
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
        Schema::dropIfExists('dish_orders');
    }
}
