<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SupplementModificateurTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modificateur_supplement', function (Blueprint $table) {
            $table->primary(['supplement_id', 'modificateur_id']);
            $table->timestamps();
            $table->softDeletes();
            $table->bigInteger('supplement_id')->unsigned()->onDelete('cascade');
            $table->bigInteger('modificateur_id')->unsigned()->onDelete('cascade');


            $table->foreign('supplement_id')
                ->references('id')
                ->on('supplements');
            $table->foreign('modificateur_id')
                ->references('id')
                ->on('modificateurs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
