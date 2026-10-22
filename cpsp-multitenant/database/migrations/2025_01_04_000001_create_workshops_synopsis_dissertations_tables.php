<?php

declare(strict_types=1);

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
        // 1. Trainee Workshops Table
        Schema::create('trainee_workshops', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('user_id');
            $table->string('workshop_name', 255);
            $table->string('time_duration', 100)->default('3 Days');
            $table->string('status', 100)->default('Completed');
            $table->date('status_date')->nullable();
            $table->timestamps();

            $table->index('tenant_id');
            $table->index('user_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        // 2. Trainee Synopses Table
        Schema::create('trainee_synopses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('user_id');
            $table->string('synopsis_name', 500);
            $table->string('status', 100)->default('Approved');
            $table->date('status_date')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index('tenant_id');
            $table->index('user_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        // 3. Trainee Dissertations Table
        Schema::create('trainee_dissertations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('user_id');
            $table->string('dissertation_title', 500)->nullable();
            $table->date('application_date')->nullable();
            $table->string('status', 100)->default('Submitted');
            $table->date('status_date')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index('tenant_id');
            $table->index('user_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainee_dissertations');
        Schema::dropIfExists('trainee_synopses');
        Schema::dropIfExists('trainee_workshops');
    }
};
