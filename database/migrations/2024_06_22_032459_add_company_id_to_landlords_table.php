<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('landlords', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->nullable(); // Vous pouvez ajuster le type de données selon vos besoins
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('landlords', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });
    }

};
