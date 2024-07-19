<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTwoPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_month', 7)->nullable()->after('amount');
            $table->string('payment_type')->nullable()->after('payment_month');
            $table->decimal('tva_amount', 10, 2)->default(0.00)->after('payment_type');
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
            $table->dropColumn('payment_month');
            $table->dropColumn('payment_type');
            $table->dropColumn('tva_amount');
        });
    }
}
