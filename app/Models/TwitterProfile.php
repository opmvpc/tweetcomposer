<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TwitterProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'twitter_id',
        'nickname',
        'avatar',
        'token',
        'token_secret',
    ];

    protected $hidden = [
        'token',
        'token_secret',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getUserProfiles()
    {
        return static::query()->where('user_id', Auth::id())->get()->map(function ($profile) {
            return [
                'id' => $profile->id,
                'twitter_id' => $profile->twitter_id,
                'nickname' => $profile->nickname,
                'avatar' => $profile->avatar,
            ];
        });
    }

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::deleting(function (TwitterProfile $twitterProfile) {
            $twitterProfile->threads->each(function (Thread $thread) {
                $thread->tweets()->get()->each(function ($tweet) {
                    Storage::disk('local')->deleteDirectory("public/users/{$tweet->thread->user_id}/tweets/{$tweet->id}");
                });
            });
        });
    }
}
