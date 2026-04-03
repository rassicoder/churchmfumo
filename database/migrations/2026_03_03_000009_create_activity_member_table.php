<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activity_member', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('activity_id');
            $table->uuid('member_id');
            $table->string('attendance_status')->default('registered');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->unique(['activity_id', 'member_id'], 'activity_member_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_member');
    }
};
