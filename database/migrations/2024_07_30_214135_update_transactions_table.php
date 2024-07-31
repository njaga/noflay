<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Ajout des nouvelles colonnes
            $table->unsignedBigInteger('property_id')->nullable()->after('description');
            $table->unsignedBigInteger('landlord_id')->nullable()->after('property_id');
            $table->unsignedBigInteger('tenant_id')->nullable()->after('landlord_id');
            $table->unsignedBigInteger('contract_id')->nullable()->after('tenant_id');

            // Modification de la colonne 'type'
            $table->enum('type', ['EXPENSE', 'PAYMENT', 'DEPOSIT', 'REFUND'])->change();

            // Ajout des clés étrangères
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('set null');
            $table->foreign('landlord_id')->references('id')->on('landlords')->onDelete('set null');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('set null');
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('set null');

            // Changement du moteur de la table à InnoDB pour supporter les clés étrangères
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Suppression des clés étrangères
            $table->dropForeign(['property_id']);
            $table->dropForeign(['landlord_id']);
            $table->dropForeign(['tenant_id']);
            $table->dropForeign(['contract_id']);

            // Suppression des colonnes ajoutées
            $table->dropColumn(['property_id', 'landlord_id', 'tenant_id', 'contract_id']);

            // Rétablissement de la colonne 'type' à son état d'origine
            $table->string('type')->change();
        });
    }
}
