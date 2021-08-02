<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_infos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('rib');
            $table->string('address');
            $table->integer('longitude');
            $table->integer('latitude');
            $table->integer('tel');
            $table->string('secret_key_stripe')
                ->nullable();
            $table->string('public_key_stripe')
                ->nullable();
            $table->string('secret_key_paypal')
                ->nullable();
            $table->string('public_key_paypal')
                ->nullable();
            $table->string('nom_restaurant')
                ->nullable();
            $table->string('num_siret')
                ->nullable();
            $table->string('num_siren')
                ->nullable();
            $table->string('num_tva_intercommunautaire')
                ->nullable();
            $table->string('logo')
                ->nullable();
            $table->string('numero_tva')
                ->nullable();
            $table->bigInteger('user_id')
                ->unsigned();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
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
        Schema::dropIfExists('restaurant_infos');
    }
}
