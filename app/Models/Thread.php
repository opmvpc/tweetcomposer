<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'scheduled_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tweets()
    {
        return $this->hasMany(Tweet::class);
    }

    public function scopeDrafts($query)
    {
        return $query
            ->whereNull('scheduled_at')
        ;
    }

    public function scopeScheduled($query)
    {
        return $query
            ->where('scheduled_at', '<', now())
        ;
    }

    public function scopePosted($query)
    {
        return $query
            ->where('scheduled_at', '>=', now())
        ;
    }

    public static function getDrafts()
    {
        return static::drafts()
            ->where('user_id', auth()->user()->id)
            ->orderBy('updated_at', 'desc')
            ->get()
        ;
    }

    public static function getScheduled()
    {
        return static::scheduled()
            ->where('user_id', auth()->user()->id)
            ->orderBy('scheduled_at', 'asc')
            ->get()
        ;
    }

    public static function getPosted()
    {
        return static::posted()
            ->where('user_id', auth()->user()->id)
            ->orderBy('scheduled_at', 'asc')
            ->get()
        ;
    }

    public function getTweetAndReplies()
    {
        return $this->tweets()
            ->with('media')
            ->get()
            ->map(function ($tweet) {
                return $tweet->only(
                    'id',
                    'content',
                    'media',
                );
            })
        ;
    }
}
