<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('published_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('user_id');
            $table->date('pub_date')->nullable();
            $table->string('pub_title', 500)->default('');
            // cpsp1 field: full combined reference
            $table->text('full_ref')->nullable();
            // cpsp2 fields: separated
            $table->string('pub_journal', 500)->default('');
            $table->string('pub_authors', 500)->default('');
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
        Schema::dropIfExists('published_entries');
    }
};
