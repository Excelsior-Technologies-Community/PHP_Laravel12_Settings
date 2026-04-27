<?php

namespace App\Http\Controllers;

use App\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\SettingLog;

class SettingsController extends Controller
{
   public function index(GeneralSettings $settings)
{
    $logs = \App\Models\SettingLog::latest()->take(5)->get();

    return view('settings.index', compact('settings', 'logs'));
}

    public function update(Request $request, GeneralSettings $settings)
    {
        // ✅ Validation
        $request->validate([
            'site_name' => 'required|string|max:255',
        ]);

        // ✅ Store old values (for history)
        $oldName = $settings->site_name;
        $oldStatus = $settings->site_active;

        // ✅ Update values
        $settings->site_name = $request->site_name;
        $settings->site_active = $request->has('site_active');
        $settings->save();

        // ✅ Save history log
        SettingLog::create([
            'site_name_old' => $oldName,
            'site_name_new' => $settings->site_name,
            'status_old' => $oldStatus,
            'status_new' => $settings->site_active,
        ]);

        // ✅ Cache settings
        Cache::put('settings', $settings, 3600);

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }
}