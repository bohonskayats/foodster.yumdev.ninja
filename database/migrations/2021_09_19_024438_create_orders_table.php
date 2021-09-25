<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            
            $table->text('client_comment');
            $table->text('manager_comment');

            $table->integer('user_id');
            $table->integer('address_id');
            $table->integer('payment_id');
           
            $table->integer('status');

           
            $table->decimal('total_price', $precision = 8, $scale = 2);
            $table->decimal('discount_value', $precision = 8, $scale = 2);
            $table->decimal('delivery_price', $precision = 8, $scale = 2);
            $table->integer('items_count');
            $table->timestamp('day_deliver')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->timestamp('time_deliver')->default(DB::raw('CURRENT_TIMESTAMP'));


            $table->timestamp('payment_complete')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->timestamp('delivery_complete')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('checkout_complete')->default(DB::raw('CURRENT_TIMESTAMP'));


           // $table->integer('order');
           // $table->integer('publish');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
