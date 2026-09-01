<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presented_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('user_id');
            $table->date('rec_date')->nullable();
            $table->string('rec_title', 500)->default('');
            $table->string('rec_venue', 500)->default('');
            // cpsp1 field
            $table->text('conf_name')->nullable();
            // cpsp2 field
            $table->string('rec_type', 50)->default('');
            $table->string('program', 20)->default('');
            $table->string('std_post', 10)->default('No');
            $table->string('entry_status', 40)->default('Draft');
            $table->dateTime('approved_at')->nullable();
            $table->timestamps();

            $table->index('tenant_id');
            $table->index('user_id');
            $table->index('program');

            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presented_entries');
    }
};
