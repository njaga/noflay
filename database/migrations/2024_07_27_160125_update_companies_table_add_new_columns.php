<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCompaniesTableAddNewColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('logo')->nullable()->after('website');
            $table->string('NINEA')->nullable()->after('logo');
            $table->string('RCCM')->nullable()->after('NINEA');
            $table->unsignedBigInteger('representant_id')->nullable()->after('RCCM');

            // Ajouter une clé étrangère si le représentant est un utilisateur
            $table->foreign('representant_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['logo', 'NINEA', 'RCCM', 'representant_id']);
        });
    }
}
