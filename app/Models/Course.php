<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
