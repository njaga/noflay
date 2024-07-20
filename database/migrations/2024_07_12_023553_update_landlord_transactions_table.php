<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLandlordTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('landlord_transactions', function (Blueprint $table) {
            $table->string('status')->after('cash_amount')->default('Pending');
        });

        Schema::table('landlord_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('landlord_transactions', 'tva_amount')) {
                $table->decimal('tva_amount', 10, 2)->default(0.00)->after('description');
            }
            if (!Schema::hasColumn('landlord_transactions', 'total_amount')) {
                $table->decimal('total_amount', 10, 2)->default(0.00)->after('tva_amount');
            }
            if (!Schema::hasColumn('landlord_transactions', 'commission_amount')) {
                $table->decimal('commission_amount', 10, 2)->nullable()->after('total_amount');
            }
            if (!Schema::hasColumn('landlord_transactions', 'net_amount')) {
                $table->decimal('net_amount', 10, 2)->nullable()->after('commission_amount');
            }
            if (!Schema::hasColumn('landlord_transactions', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('net_amount');
            }
            if (!Schema::hasColumn('landlord_transactions', 'cheque_number')) {
                $table->string('cheque_number')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('landlord_transactions', 'cheque_amount')) {
                $table->decimal('cheque_amount', 10, 2)->nullable()->after('cheque_number');
            }
            if (!Schema::hasColumn('landlord_transactions', 'cash_amount')) {
                $table->decimal('cash_amount', 10, 2)->nullable()->after('cheque_amount');
            }
            if (!Schema::hasColumn('landlord_transactions', 'month')) {
                $table->string('month', 7)->nullable()->after('cash_amount');
            }
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
            $table->dropColumn('status');
        });

        Schema::table('landlord_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('landlord_transactions', 'tva_amount')) {
                $table->dropColumn('tva_amount');
            }
            if (Schema::hasColumn('landlord_transactions', 'total_amount')) {
                $table->dropColumn('total_amount');
            }
            if (Schema::hasColumn('landlord_transactions', 'commission_amount')) {
                $table->dropColumn('commission_amount');
            }
            if (Schema::hasColumn('landlord_transactions', 'net_amount')) {
                $table->dropColumn('net_amount');
            }
            if (Schema::hasColumn('landlord_transactions', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
            if (Schema::hasColumn('landlord_transactions', 'cheque_number')) {
                $table->dropColumn('cheque_number');
            }
            if (Schema::hasColumn('landlord_transactions', 'cheque_amount')) {
                $table->dropColumn('cheque_amount');
            }
            if (Schema::hasColumn('landlord_transactions', 'cash_amount')) {
                $table->dropColumn('cash_amount');
            }
            if (Schema::hasColumn('landlord_transactions', 'month')) {
                $table->dropColumn('month');
            }
        });
    }
}
