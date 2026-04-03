<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('action_items', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('meeting_id');
            $table->text('description');
            $table->uuid('responsible_leader_id');
            $table->date('deadline');
            $table->string('status')->default('pending');
            $table->softDeletes();
            $table->timestamps();

            $table->index('meeting_id');
            $table->index('responsible_leader_id');
            $table->index('deadline');
            $table->index('status');

            $table->foreign('meeting_id')->references('id')->on('meetings')->onDelete('cascade');
            $table->foreign('responsible_leader_id')->references('id')->on('leaders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('action_items');
    }
};
