<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'bio',
        'fakultas',
        'jurusan',
        'angkatan',
        'avatar_path',
        'is_first_login',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Helper untuk mendapatkan URL Avatar
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar_path) {
            return asset('storage/' . $this->avatar_path);
        }
        // Return default avatar jika belum upload
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->user->name) . '&background=random';
    }
}