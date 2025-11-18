<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // tambah NIM jika belum ada
            if (!Schema::hasColumn('users', 'nim')) {
                $table->string('nim', 50)->unique()->after('id');
            }

            // role student / instructor / admin
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['student','instructor','admin'])
                      ->default('student')
                      ->after('password');
            }

            // wajib ganti password pertama login
            if (!Schema::hasColumn('users', 'must_change_password')) {
                $table->boolean('must_change_password')
                      ->default(true)
                      ->after('role');
            }

            // wajib lengkapi profil
            if (!Schema::hasColumn('users', 'profile_completed')) {
                $table->boolean('profile_completed')
                      ->default(false)
                      ->after('must_change_password');
            }

            // soft deletes (opsional)
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nim',
                'role',
                'must_change_password',
                'profile_completed',
                'deleted_at'
            ]);
        });
    }
};
