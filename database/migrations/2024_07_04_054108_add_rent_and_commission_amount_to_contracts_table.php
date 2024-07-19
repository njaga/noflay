<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRentAndCommissionAmountToContractsTable extends Migration
{
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->decimal('rent_amount', 15, 2)->nullable()->after('property_id');
            $table->decimal('commission_amount', 15, 2)->nullable()->after('rent_amount');
        });
    }

    public function down()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('rent_amount');
            $table->dropColumn('commission_amount');
        });
    }
}
