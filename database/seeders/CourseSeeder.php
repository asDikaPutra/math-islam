<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run()
    {
        // 1. Buat User Admin/Contoh (Jika belum ada)
        $user = User::firstOrCreate(
            ['email' => 'dikaputramaulana86@gmail.com'], // Ganti dengan email login Anda
            [
                'name' => 'Dika Putra Maulana',
                'password' => bcrypt('password123'),
                'nim' => '1227010010'
            ]
        );

        // 2. Buat Kursus Contoh: "Dasar Pemrograman Web"
        $course1 = Course::create([
            'title' => 'Dasar Pemrograman Web',
            'slug' => 'dasar-pemrograman-web',
            'description' => 'Belajar HTML, CSS, dan dasar JavaScript untuk pemula.',
            'level' => 'beginner',
            'is_published' => true,
            'thumbnail' => 'https://images.unsplash.com/photo-1587620962725-abab7fe55159?w=800&q=80', // Gambar placeholder
        ]);

        // 3. Buat Modul & Pelajaran untuk Kursus 1
        $module1 = Module::create(['course_id' => $course1->id, 'title' => 'Pengenalan HTML', 'order' => 1]);
        
        Lesson::create([
            'module_id' => $module1->id,
            'title' => 'Apa itu HTML?',
            'slug' => 'apa-itu-html',
            'type' => 'video',
            'duration_minutes' => 10,
            'content' => 'HTML adalah bahasa markup standar...',
            'order' => 1
        ]);
        
        Lesson::create([
            'module_id' => $module1->id,
            'title' => 'Struktur Dasar Halaman',
            'slug' => 'struktur-dasar',
            'type' => 'text',
            'duration_minutes' => 5,
            'content' => 'Setiap halaman HTML memiliki struktur head dan body...',
            'order' => 2
        ]);

        // 4. Daftarkan User ke Kursus ini (Enrollment)
        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course1->id,
            'progress_percent' => 50, // Pura-pura sudah belajar 50%
            'enrolled_at' => now(),
        ]);

        // ---------------------------------------------------------

        // 5. Buat Kursus Contoh 2: "Laravel untuk Pemula" (Sudah Selesai)
        $course2 = Course::create([
            'title' => 'Laravel 10 untuk Pemula',
            'slug' => 'laravel-10-pemula',
            'description' => 'Membangun aplikasi modern dengan PHP Laravel.',
            'level' => 'intermediate',
            'is_published' => true,
            'thumbnail' => 'https://images.unsplash.com/photo-1600697395543-ef3efbd847eb?w=800&q=80',
        ]);
        
        $mod2 = Module::create(['course_id' => $course2->id, 'title' => 'Instalasi', 'order' => 1]);
        Lesson::create(['module_id' => $mod2->id, 'title' => 'Setup Composer', 'slug' => 'setup-composer', 'type' => 'text', 'order' => 1]);

        // Enroll User (Sudah Selesai / Completed)
        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course2->id,
            'progress_percent' => 100,
            'is_completed' => true,
            'completed_at' => now()->subDays(2),
            'enrolled_at' => now()->subMonth(),
        ]);
        
        // Beri Sertifikat Dummy
        \App\Models\Certificate::create([
            'user_id' => $user->id,
            'course_id' => $course2->id,
            'certificate_code' => 'CERT-' . Str::random(10),
            'issued_at' => now(),
        ]);
    }
}