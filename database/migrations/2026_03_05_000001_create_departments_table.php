<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('church_id');
            $table->string('name');
            $table->uuid('leader_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('church_id');
            $table->index('leader_id');

            $table->foreign('church_id')->references('id')->on('churches')->onDelete('cascade');
            $table->foreign('leader_id')->references('id')->on('leaders')->nullOnDelete();
            $table->unique(['church_id', 'name'], 'departments_church_name_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
