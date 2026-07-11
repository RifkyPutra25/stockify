<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $appName = Setting::get('app_name', 'Stockify');
        $appLogo = Setting::get('app_logo');

        return view('settings.edit', compact('appName', 'appLogo'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:100',
            'app_logo' => 'nullable|image|max:1024',
        ]);

        Setting::set('app_name', $request->app_name);

        if ($request->hasFile('app_logo')) {
            $path = $request->file('app_logo')->store('settings', 'public');
            Setting::set('app_logo', $path);
        }

        ActivityLog::record('update', 'Memperbarui pengaturan umum aplikasi');

        return redirect()->route('settings.edit')->with('success', 'Pengaturan berhasil disimpan.');
    }
}