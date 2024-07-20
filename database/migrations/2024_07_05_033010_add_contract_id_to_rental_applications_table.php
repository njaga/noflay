<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContractIdToRentalApplicationsTable extends Migration
{
    public function up()
    {
        Schema::table('rental_applications', function (Blueprint $table) {
            $table->unsignedBigInteger('contract_id')->nullable()->after('property_id');

            // Assuming you have a contracts table and want to create a foreign key constraint
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('rental_applications', function (Blueprint $table) {
            $table->dropForeign(['contract_id']);
            $table->dropColumn('contract_id');
        });
    }
}
