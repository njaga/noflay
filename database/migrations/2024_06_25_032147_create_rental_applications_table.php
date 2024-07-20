<?php

// database/migrations/2024_06_24_000006_create_rental_applications_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentalApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create('rental_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('contract_type');
            $table->date('start_date');
            $table->integer('duration'); // in months
            $table->decimal('deposit_amount', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rental_applications');
    }
}
