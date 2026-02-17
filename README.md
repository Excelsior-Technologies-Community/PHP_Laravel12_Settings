# PHP_Laravel12_Settings

## Project Introduction

PHP_Laravel12_Settings is a demonstration project that explains how to install, configure, and use the Spatie Laravel Settings package within a Laravel 12 application.
The project focuses on managing global application settings in a structured, type-safe, and database-driven manner, allowing configuration values to be updated dynamically without modifying the source code.

------------------------------------------------------------------------

## Project Overview

This project provides a complete step-by-step implementation of a settings management system in Laravel 12.
It covers package installation, database configuration, creation of a settings class and migration, controller integration, routing, and a modern user interface built with Tailwind CSS for updating settings such as site name and website status.

The goal is to demonstrate a clean, scalable, and production-ready approach for handling application-wide configuration in real-world Laravel projects while maintaining maintainable and organized code structure.

------------------------------------------------------------------------

## Features

- Structured and type-safe settings management
- Database-driven configuration storage
- Easy update of global application settings
- Clean MVC architecture in Laravel 12
- Modern Tailwind CSS user interface
- Scalable and production-ready implementation

------------------------------------------------------------------------

## Requirements

- PHP 8.2+
- Composer
- MySQL or compatible database
- Laravel 12 compatible environment

------------------------------------------------------------------------

## Step 1 --- Create Laravel 12 Project

``` bash
composer create-project laravel/laravel PHP_Laravel12_Settings
cd PHP_Laravel12_Settings
```

Run the server:

``` bash
php artisan serve
```

------------------------------------------------------------------------

## Step 2 --- Install Spatie Laravel Settings

``` bash
composer require spatie/laravel-settings
```

Publish configuration and migration:

``` bash
php artisan vendor:publish --provider="Spatie\LaravelSettings\LaravelSettingsServiceProvider"
```

This will publish:

- config/settings.php

- settings migration file

------------------------------------------------------------------------

## Step 3 — Configure Database

Open .env and set your database:

```.env
DB_CONNECTION=mysql
DB_DATABASE=laravel12_settings
DB_USERNAME=root
DB_PASSWORD=
```

Run migration:

```bash
php artisan migrate
```

This creates the settings storage table in the database.

------------------------------------------------------------------------

## Step 4 --- Create First Settings Class

``` bash
php artisan make:settings GeneralSettings
```

File created:

    app/Settings/GeneralSettings.php

If not work then create file Manually 

``` php
<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_name;
    public bool $site_active;

    public static function group(): string
    {
        return 'general';
    }
}
```

------------------------------------------------------------------------

## Step 5 --- Create Settings Migration

If already show this file then not run this command otherwise run 

``` bash
php artisan make:settings-migration create_general_settings
```
Open created file in:

database/settings


Edit migration:

``` php
<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration {
    public function up(): void
    {
        $this->migrator->add('general.site_name', 'My Laravel App');
        $this->migrator->add('general.site_active', true);
    }
};
```

Run:

``` bash
php artisan migrate
```

------------------------------------------------------------------------

## Step 6 --- Use Settings in Controller

Create controller:

``` bash
php artisan make:controller SettingsController
```

### Controller Code

``` php
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
        $settings->site_name = $request->site_name;
        $settings->site_active = $request->has('site_active');
        $settings->save();

        return redirect()->back()->with('success', 'Settings updated successfully!');
    }
}
```

------------------------------------------------------------------------

## Step 7 --- Create Blade View

Create file:

    resources/views/settings/index.blade.php

### Blade Code

``` blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>General Settings</title>

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-2xl bg-white shadow-xl rounded-2xl p-8">

        {{-- Header --}}
        <div class="mb-6 border-b pb-4">
            <h1 class="text-2xl font-bold text-gray-800">
                ⚙️ General Settings
            </h1>
            <p class="text-gray-500 text-sm">
                Manage your website configuration and preferences.
            </p>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 p-3 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Site Name --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Site Name
                </label>

                <input
                    type="text"
                    name="site_name"
                    value="{{ $settings->site_name }}"
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 px-4 py-2 text-sm shadow-sm"
                    placeholder="Enter your website name"
                    required
                >
            </div>

            {{-- Site Active Toggle --}}
            <div class="flex items-center justify-between bg-gray-50 border rounded-lg p-4">
                <div>
                    <h3 class="text-sm font-semibold text-gray-800">Website Status</h3>
                    <p class="text-xs text-gray-500">Enable or disable the website visibility.</p>
                </div>

                {{-- TOGGLE --}}
                <label class="relative inline-flex items-center cursor-pointer">

                    {{-- Checkbox --}}
                    <input
                        type="checkbox"
                        name="site_active"
                        class="sr-only peer"
                        {{ $settings->site_active ? 'checked' : '' }}
                    >

                    {{-- Track --}}
                    <div class="w-12 h-7 bg-gray-300 rounded-full
                                peer-checked:bg-blue-600
                                transition-colors duration-300">
                    </div>

                    {{-- Moving Circle (sibling of peer → will slide) --}}
                    <span class="absolute left-1 top-1 w-5 h-5 bg-white rounded-full shadow
                                 transition-transform duration-300
                                 peer-checked:translate-x-5">
                    </span>

                </label>
            </div>

            {{-- Submit Button --}}
            <div class="pt-4">
                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg shadow-md transition duration-200"
                >
                    Save Settings
                </button>
            </div>
        </form>
    </div>

</body>
</html>
```

------------------------------------------------------------------------

## Step 8 --- Add Routes

Edit:

    routes/web.php

``` php
use App\Http\Controllers\SettingsController;

Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
```

------------------------------------------------------------------------

## Step 9 --- Test the Project

1.  Start server: `php artisan serve`
2.  Open: **http://127.0.0.1:8000/settings**
3.  Update values and verify they persist in database.

------------------------------------------------------------------------

## Output

### ON Website Status

<img width="1919" height="1031" alt="Screenshot 2026-02-17 125659" src="https://github.com/user-attachments/assets/c39e4bfb-fa0f-4938-8c97-72c18987ac45" />

### OFF Website Status

<img width="1915" height="1029" alt="Screenshot 2026-02-17 125622" src="https://github.com/user-attachments/assets/d550f5a1-3f04-440e-aa2c-295a780005f3" />

------------------------------------------------------------------------

## Project Structure

```
PHP_Laravel12_Settings/
│
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │        └── SettingsController.php
│   │
│   └── Settings/
│        └── GeneralSettings.php
│
├── bootstrap/
│
├── config/
│   └── settings.php          ← (optional config file)
│
├── database/
│   ├── migrations/
│   └── settings/
│        └── 2026_02_17_070525_general_settings.php
│
├── public/
│
├── resources/
│   └── views/
│        └── settings/
│             └── index.blade.php
│
├── routes/
│   └── web.php
│
├── storage/
│
├── tests/
│
├── .env
├── artisan
├── composer.json
└── README.md
```
------------------------------------------------------------------------

Your PHP_Laravel12_Settings Project is now ready!
