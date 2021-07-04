<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->date('date_de_naissance')->nullable();
            $table->string('image')->nullable()->default(null);
            $table->string('nomimage')->nullable()->default(null);
            $table->string('email')->unique();
            $table->integer('numero_de_telephone')->nullable();
            $table->boolean('premium')->default(false);
            $table->integer('number_of_ban')->default(0);
            $table->dateTime('banned_at')->nullable()->default(null);
           $table->timestamp('email_verified_at')->nullable();
            $table->integer('is_verified')->default(0);
            $table->string('verification_code')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
