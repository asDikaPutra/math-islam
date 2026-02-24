<?php

namespace App\Models;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Model;
use APP\Models\modules;

class Course extends Model
{
    protected $guarded = ['id'];

    // Relasi: Kursus punya banyak Modul
    public function modules()
    {
        return $this->hasMany(Module::class)->orderBy('order');
    }

    // Relasi: Kursus punya banyak Siswa (melalui Enrollments)
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    // Relasi: Kursus punya banyak Siswa (langsung user)
    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments')
            ->withPivot('progress_percent', 'completed_at')
            ->withTimestamps();
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function learning(Course $course, $lessonSlug = null)
{
    // 1. Security: Cek apakah user sudah terdaftar
    $isEnrolled = request()->user()->enrollments()
        ->where('course_id', $course->id)
        ->exists();

    if (!$isEnrolled) {
        abort(403, 'Anda belum mendaftar di kursus ini.');
    }

    // 2. Load Modul dan Lessons (Eager Loading biar ringan)
    $course->load(['modules.lessons' => function($q) {
        $q->orderBy('order', 'asc');
    }]);

    // 3. Tentukan Lesson mana yang dibuka
    $currentLesson = null;

    if ($lessonSlug) {
        // Jika ada slug di URL, cari lesson berdasarkan slug
        $currentLesson = Lesson::where('slug', $lessonSlug)
            ->whereHas('module', function($q) use ($course) {
                $q->where('course_id', $course->id);
            })->firstOrFail();
    } else {
        // Jika URL cuma /learning/nama-kursus, ambil lesson PERTAMA dari modul PERTAMA
        $firstModule = $course->modules->sortBy('order')->first();
        if ($firstModule) {
            $currentLesson = $firstModule->lessons->sortBy('order')->first();
        }
    }

    return view('student.learning', [
        'course' => $course,
        'currentLesson' => $currentLesson,
    ]);
}
}
