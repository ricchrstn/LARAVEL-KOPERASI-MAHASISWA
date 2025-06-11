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
            $table->string('firebase_uid')->nullable()->after('remember_token');
            $table->string('nim')->nullable()->after('firebase_uid');
            $table->string('faculty')->nullable()->after('nim');
            $table->string('phone')->nullable()->after('faculty');
            $table->string('role')->default('user')->after('phone'); // ✅ tambahkan role di sini
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['firebase_uid', 'nim', 'faculty', 'phone', 'role']); // ✅ drop semuanya
        });
    }
};
