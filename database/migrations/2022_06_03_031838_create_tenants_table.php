<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();

            $table->string('surname');
            $table->string('name');
            $table->string('patr');

            $table->string('phone', 11)->nullable(); // 8 9372 169 430

            $table->bigInteger('idUser')->unsigned();
            $table->foreign('idUser')->references('id')->on('users');

            $table->bigInteger('idHouse')->unsigned();
            $table->foreign('idHouse')->references('id')->on('houses');

            $table->string('room');

            $table->timestamps();
            $table->archivedAt();
        });

    }

    public function down()
    {
        Schema::dropIfExists('tenants');
    }
}
