<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'scheduled_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function medium()
    {
        return $this->hasMany(Medium::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function child()
    {
        return $this->hasOne(Tweet::class, 'tweet_id');
    }

    public function parent()
    {
        return $this->belongsTo(Tweet::class, 'tweet_id');
    }

    /**
     * Get a tweet and all of its children.
     *
     * @return \Illuminate\Database\Eloquent\Collection|Tweet[]
     */
    public function getTweetAndReplies()
    {
        $children = collect();
        $current = $this;

        do {
            $children->push($current->only(
                'id',
                'content'
            ));
            $current = $current->child;
        } while ($current);

        return $children;
    }

    public function scopeDrafts($query)
    {
        return $query->whereNull('tweet_id')
            ->whereNull('scheduled_at')
        ;
    }

    public function scopeScheduled($query)
    {
        return $query->whereNull('tweet_id')
            ->where('scheduled_at', '<', now())
        ;
    }

    public function scopePosted($query)
    {
        return $query->whereNull('tweet_id')
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
}
