<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandlordTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('landlord_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('landlord_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['rent', 'caution', 'commission_rent', 'commission_caution', 'expense', 'payout']);
            $table->decimal('amount', 15, 2);
            $table->date('transaction_date');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('landlord_transactions');
    }
}
