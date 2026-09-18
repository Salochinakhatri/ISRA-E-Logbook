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
        // 1. Add supervisor_id on users table
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'supervisor_id')) {
                    $table->unsignedBigInteger('supervisor_id')->nullable()->after('role_id');
                    $table->foreign('supervisor_id')->references('id')->on('users')->nullOnDelete();
                }
            });
        }

        // 2. Create supervisor_trainee pivot table
        if (!Schema::hasTable('supervisor_trainee')) {
            Schema::create('supervisor_trainee', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('supervisor_id');
                $table->unsignedBigInteger('trainee_id');
                $table->timestamps();

                $table->unique(['supervisor_id', 'trainee_id'], 'uq_supervisor_trainee');
                $table->foreign('supervisor_id')->references('id')->on('users')->cascadeOnDelete();
                $table->foreign('trainee_id')->references('id')->on('users')->cascadeOnDelete();
            });
        }

        // 3. Add supervisor_id to entry tables for explicit request routing
        $entryTables = [
            'training_entries',
            'rotational_entries',
            'journal_entries',
            'presented_entries',
            'published_entries',
        ];

        foreach ($entryTables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (!Schema::hasColumn($tableName, 'supervisor_id')) {
                        $table->unsignedBigInteger('supervisor_id')->nullable()->after('user_id');
                        $table->foreign('supervisor_id')->references('id')->on('users')->nullOnDelete();
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $entryTables = [
            'training_entries',
            'rotational_entries',
            'journal_entries',
            'presented_entries',
            'published_entries',
        ];

        foreach ($entryTables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'supervisor_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropForeign(['supervisor_id']);
                    $table->dropColumn('supervisor_id');
                });
            }
        }

        Schema::dropIfExists('supervisor_trainee');

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'supervisor_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['supervisor_id']);
                $table->dropColumn('supervisor_id');
            });
        }
    }
};
