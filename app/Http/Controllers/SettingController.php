<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        return view('settings.edit', [
            'setting' => Setting::firstOrCreate([], [
                'shop_name' => 'Moto Service WebApp',
                'shop_phone' => '',
                'shop_address' => '',
                'invoice_footer' => 'Thank you for trusting our workshop.',
            ]),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $setting = Setting::firstOrCreate([]);

        $setting->update($request->validate([
            'shop_name' => ['required', 'string', 'max:255'],
            'shop_phone' => ['nullable', 'string', 'max:100'],
            'shop_address' => ['nullable', 'string'],
            'invoice_footer' => ['nullable', 'string'],
        ]));

        return redirect()->route('settings.edit')->with('success', 'Settings saved.');
    }
}
