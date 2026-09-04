<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create 'roles' table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->string('name', 50);
            $table->string('slug', 50);
            $table->string('description')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
        });

        // 2. Add 'role_id' to 'users' table
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable()->after('user_type_id');
            $table->foreign('role_id')->references('id')->on('roles')->nullOnDelete();
        });

        // 3. Create 'role_user' pivot table for many-to-many RBAC
        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete();
            $table->unique(['user_id', 'role_id']);
        });

        // 4. Seed initial roles for each tenant (and global fallback)
        $tenants = DB::table('tenants')->get();
        $rolesToSeed = [
            ['name' => 'Trainee',       'slug' => 'trainee',       'description' => 'Postgraduate medical resident/trainee'],
            ['name' => 'Supervisor',    'slug' => 'supervisor',    'description' => 'Clinical supervisor and consultant'],
            ['name' => 'Fellow',        'slug' => 'fellow',        'description' => 'Post-fellowship subspecialty trainee'],
            ['name' => 'Administrator', 'slug' => 'administrator', 'description' => 'System administrator'],
        ];

        // Global roles (tenant_id = null)
        foreach ($rolesToSeed as $r) {
            DB::table('roles')->insertOrIgnore([
                'tenant_id'   => null,
                'name'        => $r['name'],
                'slug'        => $r['slug'],
                'description' => $r['description'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        // Per-tenant roles
        foreach ($tenants as $tenant) {
            foreach ($rolesToSeed as $r) {
                DB::table('roles')->insertOrIgnore([
                    'tenant_id'   => $tenant->id,
                    'name'        => $r['name'],
                    'slug'        => $r['slug'],
                    'description' => $r['description'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }

        // 5. Populate existing users' roles according to their user_type or username
        $users = DB::table('users')->get();
        foreach ($users as $u) {
            $userType = DB::table('user_types')->where('id', $u->user_type_id)->first();
            $roleName = $userType ? $userType->name : 'Trainee';

            // Find matching role for this tenant (or fallback to global)
            $role = DB::table('roles')
                ->where(function ($query) use ($u) {
                    $query->where('tenant_id', $u->tenant_id)->orWhereNull('tenant_id');
                })
                ->where('name', $roleName)
                ->orderByRaw('tenant_id IS NULL ASC') // prefer tenant-specific role
                ->first();

            if ($role) {
                // Update direct role_id on users table
                DB::table('users')->where('id', $u->id)->update(['role_id' => $role->id]);

                // Insert into role_user pivot table
                DB::table('role_user')->insertOrIgnore([
                    'user_id'    => $u->id,
                    'role_id'    => $role->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('role_user');

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });

        Schema::dropIfExists('roles');
    }
};
