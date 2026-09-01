<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the default Laravel users table first (we replace it with our own)
        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('user_type_id');
            $table->string('username', 100);
            $table->string('email', 255);
            $table->string('password', 255);
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'username']);
            $table->index('tenant_id');
            $table->index('user_type_id');
            $table->index('remember_token');

            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreign('user_type_id')->references('id')->on('user_types')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
