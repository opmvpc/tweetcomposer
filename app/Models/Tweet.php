<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Tweet extends Model
{
    use HasFactory;

    protected $fillable = [
        'twitter_api_id',
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

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::deleting(function (Tweet $tweet) {
            Storage::disk('local')->deleteDirectory("public/users/{$tweet->thread->user_id}/tweets/{$tweet->id}");
        });
    }
}
