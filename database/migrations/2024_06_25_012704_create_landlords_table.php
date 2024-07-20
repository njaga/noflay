<?php

// database/migrations/2024_06_24_000000_create_landlords_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandlordsTable extends Migration
{
    public function up()
    {
        Schema::create('landlords', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('address');
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('identity_number');
            $table->date('identity_expiry_date');
            $table->float('agency_percentage');
            $table->integer('contract_duration'); // duration in months
            $table->string('attachments')->nullable(); // path to attachments
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('landlords');
    }
}

