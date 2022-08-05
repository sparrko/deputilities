<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company', function (Blueprint $table) {
            $table->string('address');
            $table->string('phone');
            $table->string('email');

            $table->string('inn');
            $table->string('kpp');
            $table->string('bik');
            $table->string('bank_name');
            $table->string('bank_num');
            $table->string('bank_cor');

            $table->text('desc');

            $table->timestamps();
        });

        // DB::table('company')->insert(
        //     array(
        //         'address'       => 'г Тольятти, ул Офицерская, влд. 12, офис 2',
        //         'phone'         => '+7(8482) 52-21-04',
        //         'email'         => 'BOGATYR.TLT-SERVICE@YANDEX.RU',

        //         'inn'           => '6321467060',
        //         'kpp'           => '433353093',
        //         'bik'           => '346892233',
        //         'bank_name'     => 'Отделение № 8556 Тольятти банка ПАО «Сбербанк» (ул. Автостроителей, 68А)',
        //         'bank_num'      => '40702810636170000000',
        //         'bank_cor'      => '40222273300000002791',

        //         'desc'          => 'Данный сайт является официальным сайтом управляющей организации УК "Богатырь". Здесь вы можете получить актуальную информацию о деятельности организации, узнать свежие новости и контактную информацию, подать самостоятельно заявку в Аварийно-Диспетчерскую службу.',

        //         'created_at'    => Carbon::now(),
        //         'updated_at'    => Carbon::now(),
        //     )
        // );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company');
    }
}
