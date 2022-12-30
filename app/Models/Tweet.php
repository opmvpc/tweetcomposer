<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
    ];

    public function media()
    {
        return $this->hasMany(Medium::class);
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }
}
