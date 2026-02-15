<?php

namespace App\Services\Yandex;

use Illuminate\Support\Facades\Http;
use PHPHtmlParser\Dom;

class YandexHtmlParserProvider
{
    public function fetchReviews(string $url): array
    {
        $response = Http::get($url);

        if ($response->failed()) {
            throw new \Exception('Не удалось загрузить страницу Яндекс.Карт');
        }

        $dom = new Dom;
        $dom->loadStr($response->body());

        $reviews = [];

        foreach ($dom->find('[data-review-id]') as $reviewNode) {

            $authorNode = $reviewNode->find('.business-review-view__author-name');
            $textNode = $reviewNode->find('.business-review-view__body-text');
            $ratingNode = $reviewNode->find('[itemprop=ratingValue]');

            $reviews[] = [
                'external_id' => $reviewNode->getAttribute('data-review-id'),
                'author_name' => $authorNode->count() ? $authorNode->text : null,
                'text' => $textNode->count() ? $textNode->text : null,
                'rating' => $ratingNode->count() ? $ratingNode->getAttribute('content') : null,
                'published_at' => now(),
            ];
        }

        return $reviews;
    }
}