<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // ОБЯЗАТЕЛЬНО

class YandexSetting extends Model
{
    protected $fillable = ['url', 'user_id', 'external_id', 'rating', 'reviews_count'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviews(): HasMany // Теперь PHP поймет, что это такое
    {
        return $this->hasMany(Review::class);
    }
}