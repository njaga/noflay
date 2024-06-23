<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToPropertiesTable extends Migration
{
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('designation')->nullable();
            $table->integer('number')->nullable();
            $table->decimal('rental_price', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('designation');
            $table->dropColumn('number');
            $table->dropColumn('rental_price');
            $table->dropColumn('sale_price');
        });
    }
}
