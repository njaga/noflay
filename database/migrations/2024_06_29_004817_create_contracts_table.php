<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_contracts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('contract_type'); // 'habitation' or 'commercial'
            $table->string('file_number');
            $table->date('start_date');
            $table->string('company')->nullable(); // For commercial contracts
            $table->string('representative')->nullable(); // For commercial contracts
            $table->string('trade_register')->nullable(); // For commercial contracts
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contracts');
    }
}
