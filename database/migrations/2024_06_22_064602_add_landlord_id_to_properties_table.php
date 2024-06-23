<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLandlordIdToPropertiesTable extends Migration
{
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->unsignedBigInteger('landlord_id')->nullable();
            $table->foreign('landlord_id')->references('id')->on('landlords')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['landlord_id']);
            $table->dropColumn('landlord_id');
        });
    }
}
