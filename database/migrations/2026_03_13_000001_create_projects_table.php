<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('church_id');
            $table->uuid('department_id')->nullable();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->uuid('leader_id')->nullable();
            $table->decimal('budget', 14, 2)->default(0);
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('duration_days')->default(0);
            $table->unsignedTinyInteger('progress')->default(0);
            $table->string('status')->default('planned');
            $table->softDeletes();
            $table->timestamps();

            $table->index('church_id');
            $table->index('department_id');
            $table->index('leader_id');
            $table->index('status');

            $table->foreign('church_id')->references('id')->on('churches')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->nullOnDelete();
            $table->foreign('leader_id')->references('id')->on('leaders')->nullOnDelete();
            $table->unique(['church_id', 'name'], 'projects_church_name_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
