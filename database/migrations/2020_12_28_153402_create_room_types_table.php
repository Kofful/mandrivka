<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRoomTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->string('type');
        });
        DB::table('room_types')->insert([
            ['type' => 'Apartment'],
            ['type' => 'De Luxe'],
            ['type' => 'Duplex'],
            ['type' => 'Studio'],
            ['type' => 'Standart'],
            ['type' => 'Residence'],
            ['type' => 'Villa']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('room_types');
    }
}
