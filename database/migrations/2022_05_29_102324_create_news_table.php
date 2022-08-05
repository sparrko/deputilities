<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('src');
            $table->enum('type', ['staff', 'tenants']);
            $table->timestamps();
            $table->archivedAt();
        });
        DB::statement("ALTER TABLE news ADD icon LONGBLOB NULL");
    }

    public function down()
    {
        Schema::dropIfExists('news');
    }
}
