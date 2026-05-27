<?php

namespace App\Http\Controllers;

use App\Settings\GeneralSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Models\SettingLog;

class SettingsController extends Controller
{
    public function index(GeneralSettings $settings)
    {
        $logs = SettingLog::latest()->take(5)->get();

        return view('settings.index', compact('settings', 'logs'));
    }

    public function update(Request $request, GeneralSettings $settings)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'support_email' => 'nullable|email',
            'phone_number' => 'nullable|string|max:20',
            'maintenance_message' => 'nullable|string',
            'theme_color' => 'required|string',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $oldName = $settings->site_name;
        $oldStatus = $settings->site_active;

        if ($request->hasFile('site_logo')) {
            if ($settings->site_logo && Storage::disk('public')->exists($settings->site_logo)) {
                Storage::disk('public')->delete($settings->site_logo);
            }
            $path = $request->file('site_logo')->store('logos', 'public');
            $settings->site_logo = $path;
        }

        $settings->site_name = $request->site_name;
        $settings->site_active = $request->has('site_active');
        $settings->support_email = $request->support_email;
        $settings->phone_number = $request->phone_number;
        $settings->maintenance_message = $request->maintenance_message;
        $settings->theme_color = $request->theme_color;
        
        $settings->save();

        SettingLog::create([
            'site_name_old' => $oldName,
            'site_name_new' => $settings->site_name,
            'status_old' => $oldStatus,
            'status_new' => $settings->site_active,
        ]);

        Cache::put('settings', $settings, 3600);

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }
}