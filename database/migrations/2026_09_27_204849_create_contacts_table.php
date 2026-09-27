<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 32)->unique();
            $table->string('email')->nullable();
            $table->string('company')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_whatsapp_opt_in')->default(false);
            $table->timestamp('consented_at')->nullable();
            $table->timestamp('opted_out_at')->nullable();
            $table->string('opt_in_source')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
