<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('church_id');
            $table->string('meeting_type');
            $table->date('meeting_date');
            $table->longText('agenda')->nullable();
            $table->longText('minutes')->nullable();
            $table->uuid('created_by');
            $table->softDeletes();
            $table->timestamps();

            $table->index('church_id');
            $table->index('meeting_type');
            $table->index('meeting_date');

            $table->foreign('church_id')->references('id')->on('churches')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
