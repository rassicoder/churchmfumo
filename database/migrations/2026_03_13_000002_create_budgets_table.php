<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('budgets', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('church_id');
            $table->uuid('department_id')->nullable();
            $table->decimal('allocated_amount', 14, 2)->default(0);
            $table->unsignedInteger('year');
            $table->uuid('approved_by')->nullable();
            $table->string('status')->default('draft');
            $table->softDeletes();
            $table->timestamps();

            $table->index('church_id');
            $table->index('department_id');
            $table->index('year');
            $table->index('status');

            $table->foreign('church_id')->references('id')->on('churches')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->nullOnDelete();
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
            $table->unique(['church_id', 'department_id', 'year'], 'budgets_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};
