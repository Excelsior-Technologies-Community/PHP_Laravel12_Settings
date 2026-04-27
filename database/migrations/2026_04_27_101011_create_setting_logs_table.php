<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('setting_logs', function (Blueprint $table) {
            $table->id();
            $table->string('site_name_old')->nullable();
            $table->string('site_name_new')->nullable();
            $table->boolean('status_old')->default(0);
            $table->boolean('status_new')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_logs');
    }
};
