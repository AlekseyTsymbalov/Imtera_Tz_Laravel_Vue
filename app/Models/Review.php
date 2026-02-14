<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'yandex_setting_id',
        'external_id',
        'author_name',
        'rating',
        'text',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function yandexSetting(): BelongsTo
    {
        return $this->belongsTo(YandexSetting::class);
    }
}