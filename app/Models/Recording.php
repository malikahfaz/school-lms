<?php

// app/Models/Recording.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recording extends Model
{
    protected $fillable = [
        'class_id',
        'file_url',
        'duration',
        'size_bytes',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'class_id');
    }
}
