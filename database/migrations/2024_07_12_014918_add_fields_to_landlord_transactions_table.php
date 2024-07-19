<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_fields_to_landlord_transactions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToLandlordTransactionsTable extends Migration
{
    public function up()
    {
        Schema::table('landlord_transactions', function (Blueprint $table) {
            $table->decimal('tva_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->string('cheque_number')->nullable();
            $table->decimal('cheque_amount', 10, 2)->nullable();
            $table->decimal('cash_amount', 10, 2)->nullable();
            $table->string('month', 7)->nullable(); // Format YYYY-MM
        });
    }

    public function down()
    {
        Schema::table('landlord_transactions', function (Blueprint $table) {
            $table->dropColumn('tva_amount');
            $table->dropColumn('total_amount');
            $table->dropColumn('payment_method');
            $table->dropColumn('cheque_number');
            $table->dropColumn('cheque_amount');
            $table->dropColumn('cash_amount');
            $table->dropColumn('month');
        });
    }
}
