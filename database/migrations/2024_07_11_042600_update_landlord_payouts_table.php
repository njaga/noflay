<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLandlordPayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('landlord_payouts', function (Blueprint $table) {
            $table->unsignedBigInteger('landlord_id')->nullable()->change();
            $table->decimal('amount', 15, 2)->nullable()->change();
            $table->decimal('commission_amount', 15, 2)->nullable();
            $table->timestamp('payout_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('landlord_payouts', function (Blueprint $table) {
            $table->dropColumn('commission_amount');
            $table->dropColumn('payout_date');
        });
    }
}
