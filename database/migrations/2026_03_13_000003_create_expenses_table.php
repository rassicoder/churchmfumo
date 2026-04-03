<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('project_id');
            $table->decimal('amount', 14, 2)->default(0);
            $table->uuid('approved_by')->nullable();
            $table->date('date');
            $table->string('status')->default('pending');
            $table->softDeletes();
            $table->timestamps();

            $table->index('project_id');
            $table->index('date');
            $table->index('status');

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
