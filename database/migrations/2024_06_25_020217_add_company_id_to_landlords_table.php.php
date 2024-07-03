<?php

// database/migrations/2024_06_24_000001_add_company_id_to_landlords_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyIdToLandlordsTable extends Migration
{
    public function up()
    {
        Schema::table('landlords', function (Blueprint $table) {
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('landlords', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });
    }
}
