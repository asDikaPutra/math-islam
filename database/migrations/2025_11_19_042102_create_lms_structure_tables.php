<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Tabel KURSUS (Master Data)
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            // Instruktur/Pembuat (bisa null jika dibuat oleh admin pusat)
            $table->foreignId('instructor_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->string('title');
            $table->string('slug')->unique(); // Untuk URL (contoh: /courses/belajar-laravel)
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable(); // Foto sampul kursus
            
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->boolean('is_published')->default(false);
            
            $table->timestamps();
        });

        // 2. Tabel MODUL/BAB (Bagian dari Kursus)
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('title'); // Contoh: "Bab 1: Pengenalan"
            $table->integer('order')->default(0); // Urutan bab
            $table->timestamps();
        });

        // 3. Tabel LESSONS/MATERI (Isi dari Modul)
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug');
            $table->integer('order')->default(0); // Urutan materi
            
            // Tipe Materi: Video, Text, atau PDF
            $table->enum('type', ['video', 'text', 'pdf', 'quiz'])->default('text');
            
            // Isi Materi
            $table->text('content')->nullable(); // Untuk teks panjang
            $table->string('video_url')->nullable(); // Link YouTube/Upload
            $table->string('file_path')->nullable(); // Untuk PDF download
            $table->integer('duration_minutes')->default(0); // Estimasi durasi belajar
            
            $table->boolean('is_preview')->default(false); // Bisa ditonton tanpa login?
            $table->timestamps();
        });

        // 4. Tabel ENROLLMENTS (Siswa mengambil Kursus)
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            
            $table->timestamp('enrolled_at')->useCurrent();
            $table->integer('progress_percent')->default(0); // 0 - 100
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();
        });

        // 5. Tabel PROGRESS DETAIL (Mencatat materi mana yang SUDAH selesai)
        Schema::create('lesson_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->timestamp('completed_at')->useCurrent();
            // Agar 1 user tidak punya double data untuk lesson yang sama
            $table->unique(['user_id', 'lesson_id']); 
        });

        // 6. Tabel SERTIFIKAT
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            
            $table->string('certificate_code')->unique(); // Kode unik sertifikat
            $table->string('file_path')->nullable(); // File PDF sertifikat
            $table->timestamp('issued_at')->useCurrent();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('lesson_completions');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('lessons');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('courses');
    }
};