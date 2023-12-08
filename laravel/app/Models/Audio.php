<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{
    protected $table = 'audios';

    protected $fillable = [
        'user_id',
        'path_mp3',
        'time_start',
        'time_end',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
