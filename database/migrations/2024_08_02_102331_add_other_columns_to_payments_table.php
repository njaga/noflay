<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOtherColumnsToPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Ajout de la colonne pour la commission
            $table->decimal('commission_amount', 15, 2)->default(0.00)->after('total_amount');

            // Ajout de la colonne pour la date d'échéance
            $table->date('due_date')->nullable()->after('payment_date');

            // Ajout de la colonne pour indiquer si le paiement est en retard
            $table->boolean('is_late')->default(false)->after('is_reversed');

            // Ajout de la colonne pour le montant des pénalités de retard
            $table->decimal('late_fee', 15, 2)->default(0.00)->after('is_late');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('commission_amount');
            $table->dropColumn('due_date');
            $table->dropColumn('is_late');
            $table->dropColumn('late_fee');
        });
    }
}
