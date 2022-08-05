<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('surname');
            $table->string('name');
            $table->string('patr');
            $table->string('phone', 11); // 8 9372 169 430
            $table->enum('role', ['admin', 'staffOfficer', 'referenceOfficer', 'executor', 'dispatcher']);

            $table->date('dateBorn');

            $table->bigInteger('idUser')->unsigned();
            $table->foreign('idUser')->references('id')->on('users');

            $table->timestamps();
            $table->archivedAt();
        });
        DB::statement("ALTER TABLE staff ADD icon MEDIUMBLOB NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff');
    }
}
