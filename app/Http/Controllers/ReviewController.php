<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\YandexSetting;
use App\Services\Yandex\MockYandexReviewsProvider;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $setting = YandexSetting::where('user_id', $request->user()->id)->first();

        $sort = $request->get('sort', 'desc');

        if (!$setting) {
            return Inertia::render('Reviews/Index', [
                'reviews' => null,
                'rating' => null,
                'reviews_count' => 0,
                'needs_setup' => true,
                'sort' => $sort,
            ]);
        }

        $reviews = $setting->reviews()
            ->orderBy('published_at', $sort === 'asc' ? 'asc' : 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Reviews/Index', [
            'reviews' => $reviews,
            'rating' => $setting->rating,
            'reviews_count' => $setting->reviews_count,
            'needs_setup' => false,
            'sort' => $sort,
        ]);
    }

    public function sync(Request $request)
    {
        $setting = YandexSetting::where('user_id', $request->user()->id)->first();

        if (!$setting) {
            return redirect()->route('reviews.index');
        }

        $provider = new MockYandexReviewsProvider();
        $data = $provider->fetch();

        $setting->update([
            'rating' => $data['rating'],
            'reviews_count' => $data['reviews_count'],
        ]);

        $reviews = collect($data['reviews'])
            ->map(function ($review) use ($setting) {
                return [
                    'yandex_setting_id' => $setting->id,
                    'external_id' => $review['external_id'],
                    'author_name' => $review['author_name'],
                    'rating' => $review['rating'],
                    'text' => $review['text'],
                    'published_at' => $review['published_at'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })
            ->toArray();

        Review::upsert(
            $reviews,
            ['external_id'],
            ['yandex_setting_id', 'author_name', 'rating', 'text', 'published_at', 'updated_at']
        );

        return redirect()->route('reviews.index');
    }
}