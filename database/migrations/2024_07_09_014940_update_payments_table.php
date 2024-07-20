<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Adding new columns
            $table->unsignedBigInteger('tenant_id')->after('company_id')->nullable();

            // Adding foreign key constraints
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');

            // Ensure that contract_id and company_id columns are foreign keys
            $table->foreign('contract_id')->references('id')->on('contracts')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
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
            // Dropping the foreign key constraints
            $table->dropForeign(['tenant_id']);
            $table->dropForeign(['contract_id']);
            $table->dropForeign(['company_id']);

            // Dropping the columns
            $table->dropColumn('tenant_id');
        });
    }
}
