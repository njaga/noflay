<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('landlords', function (Blueprint $table) {
            $table->text('attachments')->change();
        });
    }

    public function down()
    {
        Schema::table('landlords', function (Blueprint $table) {
            $table->string('attachments', 255)->change();
        });
    }

};
