<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('member_leadership_position', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('member_id');
            $table->uuid('leadership_position_id');
            $table->date('appointed_at')->nullable();
            $table->date('ended_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
            $table->foreign('leadership_position_id')->references('id')->on('leadership_positions')->onDelete('cascade');
            $table->unique(['member_id', 'leadership_position_id'], 'member_position_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_leadership_position');
    }
};
