<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MigrateEndDateFromRentalApplicationsToContracts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('contracts')
            ->join('rental_applications', 'contracts.id', '=', 'rental_applications.contract_id')
            ->update([
                'contracts.end_date' => DB::raw('rental_applications.end_date')
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('contracts')->update(['end_date' => null]);
    }
}
