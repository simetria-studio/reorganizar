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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'manager', 'operator'])->default('operator')->after('email');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('role');
            $table->string('phone')->nullable()->after('email');
            $table->string('avatar')->nullable()->after('phone');
            $table->string('department')->nullable()->after('avatar');
            $table->timestamp('last_login_at')->nullable()->after('department');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
            $table->boolean('is_first_login')->default(true)->after('last_login_ip');
            $table->timestamp('password_changed_at')->nullable()->after('is_first_login');
            $table->json('permissions')->nullable()->after('password_changed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'status', 
                'phone',
                'avatar',
                'department',
                'last_login_at',
                'last_login_ip',
                'is_first_login',
                'password_changed_at',
                'permissions'
            ]);
        });
    }
};
