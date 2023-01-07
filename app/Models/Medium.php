<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Medium extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'url',
    ];

    public function tweet()
    {
        return $this->belongsTo(Tweet::class);
    }

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::deleted(function (Medium $medium) {
            if (null !== $medium->path && Storage::disk('local')->exists($medium->path)) {
                Storage::disk('local')->delete($medium->path);
            }
        });
    }
}
