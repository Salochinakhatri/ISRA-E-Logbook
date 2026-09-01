<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'training_entries',
            'rotational_entries',
            'journal_entries',
            'presented_entries',
            'published_entries',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                if (!Schema::hasColumn($table->getTable(), 'supervisor_remarks')) {
                    $table->text('supervisor_remarks')->nullable()->after('entry_status');
                }
                if (!Schema::hasColumn($table->getTable(), 'approved_by')) {
                    $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
                    $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'training_entries',
            'rotational_entries',
            'journal_entries',
            'presented_entries',
            'published_entries',
        ];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                if (Schema::hasColumn($table->getTable(), 'approved_by')) {
                    $table->dropForeign(['approved_by']);
                    $table->dropColumn('approved_by');
                }
                if (Schema::hasColumn($table->getTable(), 'supervisor_remarks')) {
                    $table->dropColumn('supervisor_remarks');
                }
            });
        }
    }
};
