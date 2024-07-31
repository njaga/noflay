<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToPaymentsTable extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'tva_amount')) {
                $table->decimal('tva_amount', 15, 2)->after('amount')->default(0);
            }
            if (!Schema::hasColumn('payments', 'total_amount')) {
                $table->decimal('total_amount', 15, 2)->after('tva_amount')->default(0);
            }
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['tva_amount', 'total_amount']);
        });
    }
}
