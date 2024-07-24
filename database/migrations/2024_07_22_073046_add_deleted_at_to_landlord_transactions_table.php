<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToLandlordTransactionsTable extends Migration
{
    public function up()
    {
        Schema::table('landlord_transactions', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('landlord_transactions', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
    }
}
