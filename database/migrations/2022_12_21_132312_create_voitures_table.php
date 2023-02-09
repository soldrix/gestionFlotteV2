<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voitures', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('marque');
            $table->string('model');
            $table->string('carburant');
            $table->date('circulation');
            $table->string('immatriculation');
            $table->boolean('statut');
            $table->integer('puissance');
            $table->string('type');
            $table->integer('nbPorte');
            $table->integer('nbPlace');
            $table->float('prix');
            $table->foreignId('id_agence')->nullable()->references('id')->on('agence')->onUpdate('cascade')->onDelete('set null');
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
        Schema::dropIfExists('voitures');
    }
};
