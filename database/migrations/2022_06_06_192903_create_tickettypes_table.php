<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTickettypesTable extends Migration
{
    public function up()
    {
        Schema::create('ticketTypes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->archivedAt();
        });

        // DB::table('ticketTypes')->insert(array('name' => 'Холодное водоснабжение'));
        // DB::table('ticketTypes')->insert(array('name' => 'Горячее водоснабжение'));
        // DB::table('ticketTypes')->insert(array('name' => 'Отопление'));
        // DB::table('ticketTypes')->insert(array('name' => 'Электротехнические работы'));
        // DB::table('ticketTypes')->insert(array('name' => 'Придомовая территория'));
        // DB::table('ticketTypes')->insert(array('name' => 'Кровли'));
    }

    public function down()
    {
        Schema::dropIfExists('ticketTypes');
    }
}
