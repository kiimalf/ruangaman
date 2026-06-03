<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expert_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('concluded_at')->nullable();
            $table->foreignId('conclusion_id')->nullable()->constrained('hypotheses')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expert_sessions');
    }
};
