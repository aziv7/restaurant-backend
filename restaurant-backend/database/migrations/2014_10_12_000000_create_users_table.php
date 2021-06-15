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
            $table->date('date de naissance')->nullable();
            $table->string('image')->nullable()->default(null);
            $table->string('nomimage')->nullable()->default(null);
            $table->string('email')->unique();
            $table->integer('numero de telephone')->nullable();
            $table->boolean('premium')->default(false);
            $table->boolean('statut')->default(false);
            $table->integer('number of ban')->default(0);
            $table->dateTime('banned at')->nullable()->default(null);
           // $table->timestamp('email_verified_at')->nullable();
           $table->string('verification_code')-> nullable();
            $table->boolean('is_verified')->default(0);

            /*$table->bigInteger('role_id')->unsigned();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');*/
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
