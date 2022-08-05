<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('idTenant')->unsigned();
            $table->foreign('idTenant')->references('id')->on('tenants');

            $table->bigInteger('idTicketType')->unsigned()->nullable();
            $table->foreign('idTicketType')->references('id')->on('ticketTypes');

            $table->string('desc');

            $table->bigInteger('idDispatcher')->unsigned()->nullable();
            $table->foreign('idDispatcher')->references('id')->on('staff');

            $table->bigInteger('idExecutor')->unsigned()->nullable();
            $table->foreign('idExecutor')->references('id')->on('staff');

            $table->bigInteger('idUserArchive')->unsigned()->nullable();
            $table->foreign('idUserArchive')->references('id')->on('users');

            $table->timestamps();
            $table->dateTime('completed_at')->nullable();

            $table->archivedAt();
            $table->string('archiveDesc')->nullable();
        });

    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
