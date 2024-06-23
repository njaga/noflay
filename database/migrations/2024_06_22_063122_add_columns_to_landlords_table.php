<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddColumnsToLandlordsTable extends Migration
{
    public function up()
    {
        Schema::table('landlords', function (Blueprint $table) {
            $table->date('registration_date')->nullable();
            $table->string('file_number')->nullable(); // Ajoutez d'abord comme nullable
            $table->integer('contract_duration')->nullable();
            $table->json('attachments')->nullable();
        });

        // Générez des numéros de dossier uniques pour les enregistrements existants
        $landlords = DB::table('landlords')->get();
        foreach ($landlords as $landlord) {
            DB::table('landlords')
                ->where('id', $landlord->id)
                ->update(['file_number' => 'LL' . str_pad($landlord->id, 6, '0', STR_PAD_LEFT)]);
        }

        // Ajoutez la contrainte unique après avoir généré les numéros de dossier
        Schema::table('landlords', function (Blueprint $table) {
            $table->unique('file_number');
        });
    }

    public function down()
    {
        Schema::table('landlords', function (Blueprint $table) {
            $table->dropUnique('landlords_file_number_unique');
            $table->dropColumn('registration_date');
            $table->dropColumn('file_number');
            $table->dropColumn('contract_duration');
            $table->dropColumn('attachments');
        });
    }
}
