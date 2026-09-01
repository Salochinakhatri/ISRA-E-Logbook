<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('user_id');
            $table->string('entry_type', 20)->default('training');
            $table->string('form_type', 10)->default('');
            $table->string('hospt_reg_no', 120)->default('');
            $table->date('date_of_admission')->nullable();
            $table->string('pt_gender', 20)->default('');
            $table->string('pt_age', 20)->default('');
            $table->string('pt_age_type', 30)->default('Year[s]');
            $table->string('pt_diagnosis', 500)->default('');
            $table->string('under_sup_name', 255)->default('');
            $table->string('level_id', 20)->default('');
            $table->string('outcome_id', 20)->default('');
            $table->mediumText('brief_desc');
            $table->string('entry_for_prog_id', 10)->default('');
            // program column: 'imm', 'mcps', 'fcps2', 'urogyn', 'obgyn', ''
            $table->string('program', 20)->default('');
            $table->json('com_ids')->nullable();
            $table->json('com_detail_ids')->nullable();
            $table->string('alt_procedure', 500)->default('');
            $table->string('std_post', 10)->default('No');
            $table->string('entry_status', 40)->default('Draft');
            $table->dateTime('approved_at')->nullable();
            $table->timestamps();

            $table->index('tenant_id');
            $table->index('user_id');
            $table->index('entry_status');
            $table->index('program');

            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_entries');
    }
};
