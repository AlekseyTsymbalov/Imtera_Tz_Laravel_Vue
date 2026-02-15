<?php

namespace App\Http\Controllers;

use App\Models\YandexSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class YandexSettingController extends Controller
{
    public function edit(Request $request)
    {
        $settings = YandexSetting::where('user_id', $request->user()->id)->first();

        return Inertia::render('Settings/Edit', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
        ]);

        YandexSetting::updateOrCreate(
            ['user_id' => $request->user()->id],
            ['url' => $request->url]
        );

        return back()->with('status', 'Данные сохранены!');
    }
}