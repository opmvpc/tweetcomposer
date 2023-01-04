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

    public function twitterProfile()
    {
        return $this->belongsTo(TwitterProfile::class);
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
            ->where('scheduled_at', '>', now())
        ;
    }

    public function scopePosted($query)
    {
        return $query
            ->where('scheduled_at', '<=', now())
        ;
    }

    public static function getDrafts()
    {
        return static::drafts()
            ->where('user_id', auth()->user()->id)
            ->with('twitterProfile')
            ->withCount('tweets')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map([__CLASS__, 'mapResult'])
        ;
    }

    public static function getScheduled()
    {
        return static::scheduled()
            ->where('user_id', auth()->user()->id)
            ->with('twitterProfile')
            ->withCount('tweets')
            ->orderBy('scheduled_at', 'asc')
            ->get()
            ->map([__CLASS__, 'mapResult'])
        ;
    }

    public static function getPosted()
    {
        return static::posted()
            ->where('user_id', auth()->user()->id)
            ->with('twitterProfile')
            ->withCount('tweets')
            ->orderBy('scheduled_at', 'desc')
            ->get()
            ->map([__CLASS__, 'mapResult'])
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

    protected static function mapResult(Thread $thread)
    {
        return [
            'id' => $thread->id,
            'title' => $thread->title,
            'tweets_count' => $thread->tweets_count,
            'twitter_profile' => $thread->twitterProfile->only(
                'nickname',
                'avatar',
            ),
            'updated_at' => $thread->updated_at->diffForHumans(),
            'scheduled_at' => null === $thread->scheduled_at ? null : $thread->scheduled_at->diffForHumans(),
        ];
    }
}
