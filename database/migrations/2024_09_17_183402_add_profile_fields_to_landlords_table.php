<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('landlords', function (Blueprint $table) {
            $table->string('company_name')->nullable();
            $table->string('tax_id')->nullable();
            $table->text('bank_info')->nullable();
        });
    }

    public function down()
    {
        Schema::table('landlords', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'tax_id', 'bank_info']);
        });
    }
};
