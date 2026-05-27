<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_name;
    public bool $site_active;
    public string $support_email;
    public string $phone_number;
    public string $maintenance_message;
    public string $theme_color;
    public ?string $site_logo;

    public static function group(): string
    {
        return 'general';
    }
}