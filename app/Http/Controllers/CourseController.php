<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module; // <--- PENTING: Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        // 1. Ambil semua kursus yang dipublish
        // Eager load 'instructor' (user) dan profilnya untuk mencegah N+1 Query
        $courses = Course::with('instructor.profile')
            ->where('is_published', true)
            ->latest() // Order by created_at desc
            ->get();

        // 2. Ambil ID kursus yang sudah diikuti user saat ini
        // Agar kita bisa ubah tombol "Daftar" menjadi "Lihat"
        $enrolledCourseIds = [];
        if (Auth::check()) {
            $enrolledCourseIds = Auth::user()->enrollments->pluck('course_id')->toArray();
        }

        return view('courses.index', [
            'courses' => $courses,
            'enrolledCourseIds' => $enrolledCourseIds,
        ]);
    }

    public function enroll(Request $request, Course $course)
    {
        $user = Auth::user();

        // Cek apakah user sudah terdaftar
        // Tambahkan () pada enrollments agar jadi Query Builder
        if ($user->enrollments->where('course_id', $course->id)->exists()) {
            return back()->with('error', 'Anda sudah terdaftar di kursus ini.');
        }

        // Proses Pendaftaran
        $user->enrollments->create([
            'course_id' => $course->id,
            'enrolled_at' => now(),
            'progress_percent' => 0
        ]);

        return redirect()->route('my-courses.index')
            ->with('success', 'Berhasil mendaftar! Selamat belajar.');
    }

    /**
     * Function BARU untuk Halaman Belajar (Learning Room)
     */
    public function learning(Course $course, $lessonSlug = null)
    {
        $user = request()->user();
        // 1. Security: Cek apakah user sudah terdaftar
        // Kita gunakan request()->user() agar aman
        $isEnrolled = request()->user()->enrollments()
            ->where('course_id', $course->id)
            ->exists();

        if (!$isEnrolled) {
            abort(403, 'Anda belum mendaftar di kursus ini.');
        }

        // 2. Load Modul dan Lessons (Eager Loading biar ringan)
        // Kita urutkan berdasarkan 'order' biar rapi
        $course->load(['modules.lessons' => function ($q) {
            $q->orderBy('order', 'asc');
        }]);

        // 3. Tentukan Lesson mana yang dibuka
        $currentLesson = null;

        if ($lessonSlug) {
            // Skenario A: Ada slug di URL (misal: /learning/laravel/install)
            // Cari lesson berdasarkan slug, tapi pastikan lesson itu milik kursus ini
            $currentLesson = Lesson::where('slug', $lessonSlug)
                ->whereHas('module', function ($q) use ($course) {
                    $q->where('course_id', $course->id);
                })->firstOrFail();
        } else {
            // Skenario B: URL cuma /learning/laravel (Tanpa lesson spesifik)
            // Ambil lesson PERTAMA dari modul PERTAMA
            $firstModule = $course->modules->sortBy('order')->first();
            if ($firstModule) {
                $currentLesson = $firstModule->lessons->sortBy('order')->first();
            }
        }

        $previousLesson = Lesson::where('module_id', $currentLesson->module_id)
            ->where('order', '<', $currentLesson->order)
            ->orderBy('order', 'desc')
            ->first();

        // SKENARIO A: Cek Kunci Antar Lesson dalam 1 Modul
        if ($previousLesson) {
            if (!$user->hasCompletedLesson($previousLesson->id)) {
                return redirect()->route('learning.show', [$course->slug, $previousLesson->slug])
                    ->with('error', 'Selesaikan materi sebelumnya terlebih dahulu!');
            }
        }

        // SKENARIO B: Cek Kunci Antar Modul (Cross-Module Locking)
        // Jika ini adalah lesson pertama di modul baru, cek apakah modul sebelumnya sudah selesai?
        // (Asumsi: Syarat selesai modul adalah lulus Kuis Akhir modul sebelumnya)

        // 1. Cek apakah ini lesson pertama di modulnya?
        $isFirstLessonInModule = Lesson::where('module_id', $currentLesson->module_id)
            ->where('order', '<', $currentLesson->order)
            ->doesntExist();

        if ($isFirstLessonInModule) {
            // Cari modul sebelumnya
            $previousModule = Module::where('course_id', $course->id)
                ->where('order', '<', $currentLesson->module->order)
                ->orderBy('order', 'desc')
                ->first();

            if ($previousModule) {
                // Cari Kuis di modul sebelumnya
                $quizLesson = $previousModule->lessons()->where('type', 'quiz')->first();

                // Jika ada kuis dan belum selesai -> Kunci!
                if ($quizLesson && !$user->hasCompletedLesson($quizLesson->id)) {
                    return redirect()->back() // Atau redirect ke kuis tersebut
                        ->with('error', 'Anda harus menyelesaikan Kuis Modul sebelumnya!');
                }
            }
        }

        // Kirim data completions ke view untuk UI Sidebar (Gembok)
        $completedLessonIds = $user->lessonCompletions()->pluck('lesson_id')->toArray();

        // 4. Tampilkan View
        return view('student.learning', [
            'course' => $course,
            'currentLesson' => $currentLesson,
            'completedLessonIds' => $completedLessonIds,
        ]);
    }

    public function markAsComplete(Lesson $lesson)
    {
        $user = request()->user();

        // 1. Simpan progres ke tabel lesson_completions
        // firstOrCreate mencegah duplikasi jika user menekan tombol berkali-kali
        $user->lessonCompletions()->firstOrCreate([
            'lesson_id' => $lesson->id
        ]);

        // (Opsional) Di sini Anda bisa menambahkan logika update persentase course di tabel enrollment

        // 2. LOGIKA MENCARI MATERI BERIKUTNYA (Next Lesson)

        // A. Cek apakah ada lesson berikutnya di MODUL YANG SAMA?
        $nextLesson = Lesson::where('module_id', $lesson->module_id)
            ->where('order', '>', $lesson->order)
            ->orderBy('order', 'asc')
            ->first();

        // B. Jika tidak ada (berarti ini materi terakhir di modul ini),
        // Cari lesson pertama di MODUL BERIKUTNYA
        if (!$nextLesson) {
            $currentModule = $lesson->module; // Pastikan relasi 'module' ada di Model Lesson

            $nextModule = \App\Models\Module::where('course_id', $currentModule->course_id)
                ->where('order', '>', $currentModule->order)
                ->orderBy('order', 'asc')
                ->first();

            if ($nextModule) {
                $nextLesson = $nextModule->lessons()->orderBy('order', 'asc')->first();
            }
        }

        // 3. REDIRECT USER
        if ($nextLesson) {
            // Jika ada materi selanjutnya, arahkan ke sana
            // Kita butuh slug course untuk URL. Load relasi module->course
            $courseSlug = $lesson->module->course->slug;

            return redirect()->route('learning.show', [
                'course' => $courseSlug,
                'lesson' => $nextLesson->slug
            ])->with('success', 'Materi sebelumnya selesai! Lanjut ke materi berikut ini.');
        } else {
            // Jika TIDAK ADA materi selanjutnya, berarti Kursus SELESAI
            return redirect()->route('my-courses.index')
                ->with('success', 'Selamat! Anda telah menyelesaikan seluruh materi di kursus ini.');
        }

        $previousLesson = Lesson::where('module_id', $currentLesson->module_id)
            ->where('order', '<', $currentLesson->order)
            ->orderBy('order', 'desc')
            ->first();

        if ($previousLesson) {
            // Jika lesson sebelumnya belum selesai...
            if (!$request->user()->hasCompletedLesson($previousLesson->id)) {

                // Cari lesson terakhir yang SUDAH diselesaikan user di kursus ini
                // agar kita bisa me-redirect mereka ke tempat yang benar
                // (Logic sederhana: redirect ke previous lesson saja)

                return redirect()->route('learning.show', [$course->slug, $previousLesson->slug])
                    ->with('error', 'Lesson terkunci! Silakan selesaikan materi "' . $previousLesson->title . '" terlebih dahulu.');
            }
        }
    }
}
