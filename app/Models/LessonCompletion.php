<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonCompletion extends Model
{
    use HasFactory;

    // TAMBAHKAN BARIS INI AGAR TIDAK ERROR
    protected $fillable = [
        'user_id',
        'lesson_id',
        'completed_at'
    ];

    public $timestamps = false;

    // Relasi ke User (Opsional tapi bagus ada)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Lesson (Opsional tapi bagus ada)
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}