<?php

declare(strict_types=1);

namespace App\Services\Yandex;

class MockYandexReviewsProvider
{
    public function fetch(): array
    {
        $reviews = [];
        for ($i = 1; $i <= 25; $i++) {
            $reviews[] = [
                'external_id' => 'review_' . $i,
                'author_name' => 'Пользователь ' . $i,
                'rating' => rand(3, 5),
                'text' => 'Тестовый отзыв номер ' . $i,
                'published_at' => now()->subDays($i),
            ];
        }

        return [
            'rating' => 4.7,
            'reviews_count' => 25,
            'reviews' => $reviews,
        ];
    }
}