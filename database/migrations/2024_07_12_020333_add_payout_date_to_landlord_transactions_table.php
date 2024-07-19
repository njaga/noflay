<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayoutDateToLandlordTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('landlord_transactions', function (Blueprint $table) {
            $table->date('payout_date')->nullable()->after('transaction_date'); // Ajoute la colonne payout_date
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('landlord_transactions', function (Blueprint $table) {
            $table->dropColumn('payout_date'); // Supprime la colonne payout_date
        });
    }
}
