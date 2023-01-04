<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
}
