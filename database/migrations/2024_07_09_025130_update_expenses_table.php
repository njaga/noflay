<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateExpensesTable extends Migration
{
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {
            // Assurez-vous que les colonnes existent
            if (!Schema::hasColumn('expenses', 'company_id')) {
                $table->unsignedBigInteger('company_id')->after('id');
            }

            if (!Schema::hasColumn('expenses', 'property_id')) {
                $table->unsignedBigInteger('property_id')->after('company_id');
            }

            if (!Schema::hasColumn('expenses', 'description')) {
                $table->string('description')->after('property_id');
            }

            if (!Schema::hasColumn('expenses', 'amount')) {
                $table->decimal('amount', 15, 2)->after('description');
            }

            if (!Schema::hasColumn('expenses', 'expense_date')) {
                $table->date('expense_date')->after('amount');
            }

            // Ajout des clés étrangères
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropForeign(['property_id']);
        });
    }
}
