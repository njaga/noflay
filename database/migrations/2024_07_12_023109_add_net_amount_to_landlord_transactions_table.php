<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNetAmountToLandlordTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('landlord_transactions', function (Blueprint $table) {
            $table->decimal('net_amount', 10, 2)->after('commission_amount')->nullable();
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
            $table->dropColumn('net_amount');
        });
    }
}
?>
