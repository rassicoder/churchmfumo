<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->uuid('church_id')->nullable()->after('id');
            $table->index('church_id');
            $table->foreign('church_id')->references('id')->on('churches')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropForeign(['church_id']);
            $table->dropIndex(['church_id']);
            $table->dropColumn('church_id');
        });
    }
};
