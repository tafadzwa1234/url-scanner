<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('scan_results', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->string('email_subject')->nullable();
            $table->string('email_sender')->nullable();
            $table->boolean('is_malicious')->default(false);
            $table->text('threat_details')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('scan_results');
    }
}; 