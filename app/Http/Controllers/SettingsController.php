<?php

namespace App\Http\Controllers;

use App\Settings\GeneralSettings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(GeneralSettings $settings)
    {
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request, GeneralSettings $settings)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
        ]);

        $settings->site_name = $request->site_name;
        $settings->site_active = $request->has('site_active');
        $settings->save();

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }
}

