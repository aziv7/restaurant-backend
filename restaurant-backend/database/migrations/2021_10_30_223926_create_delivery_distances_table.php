<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryDistancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_distances', function (Blueprint $table) {
            $table->id();
            $table->integer('distance')->nullable();
            $table->decimal('price',6,3)->nullable();
            $table->bigInteger('restaurant_info_id')->unsigned()->nullable();
            $table->foreign('restaurant_info_id')->references('id')->on('restaurant_infos')->onDelete('cascade');
            $table->softDeletes();
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
        Schema::dropIfExists('delivery_distances');
    }
}
