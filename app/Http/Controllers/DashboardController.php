<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // 1. Eager Load Relasi
        // Kita memuat relasi 'profile', 'enrollments' (beserta kursusnya), dan 'certificates'
        // tujuannya agar database tidak dipanggil berulang kali (menghemat performa).
        $user->load(['profile', 'enrollments.course', 'certificates']);

        // 2. Hitung Kursus yang Sedang Diikuti
        // Menghitung jumlah data di tabel enrollments milik user ini
        $enrolledCourses = $user->enrollments->count();

        // 3. Hitung Kursus yang Sudah Selesai
        // Mengecek data enrollment yang kolom 'completed_at'-nya TIDAK kosong (not null)
        $completedCourses = $user->enrollments->whereNotNull('completed_at')->count();

        // 4. Hitung Jumlah Sertifikat
        $certificates = $user->certificates->count();

        // 5. Hitung Total Jam Belajar
        // Kita menjumlahkan durasi (duration_hours) dari setiap kursus yang diambil user.
        // Jika data durasi kosong, kita anggap 0.
        $totalHours = $user->enrollments->sum(function($enrollment) {
            return $enrollment->course->duration_hours ?? 0; 
        });

        // 6. Kirim Data ke View (Tampilan)
        // Kita mengirim variabel $user dan array $stats ke file 'resources/views/dashboard.blade.php'
        return view('dashboard', [
            'user' => $user,
            'stats' => [
                'enrolled' => $enrolledCourses,
                'completed' => $completedCourses,
                'certificates' => $certificates,
                'totalHours' => $totalHours,
            ]
        ]);
    }
}