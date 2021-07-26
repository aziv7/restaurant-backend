<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('note')->length(2)->unsigned();
            $table->bigInteger('user_id')->unsigned()->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
            $table->bigInteger('plat_id')->unsigned()->onDelete('cascade');
            $table->foreign('plat_id')
                ->references('id')
                ->on('plats');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ratings');
    }
}
