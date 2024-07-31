<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToTransactionsTable extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'company_id')) {
                $table->unsignedBigInteger('company_id')->after('id')->nullable();
                $table->foreign('company_id')->references('id')->on('companies')->onDelete('set null');
            }

            if (!Schema::hasColumn('transactions', 'payment_id')) {
                $table->unsignedBigInteger('payment_id')->after('company_id')->nullable();
                $table->foreign('payment_id')->references('id')->on('payments')->onDelete('set null');
            }

            if (!Schema::hasColumn('transactions', 'contract_id')) {
                $table->unsignedBigInteger('contract_id')->after('payment_id')->nullable();
                $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('set null');
            }

            if (!Schema::hasColumn('transactions', 'expense_id')) {
                $table->unsignedBigInteger('expense_id')->after('contract_id')->nullable();
                $table->foreign('expense_id')->references('id')->on('expenses')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');

            $table->dropForeign(['payment_id']);
            $table->dropColumn('payment_id');

            $table->dropForeign(['contract_id']);
            $table->dropColumn('contract_id');

            $table->dropForeign(['expense_id']);
            $table->dropColumn('expense_id');
        });
    }
}
