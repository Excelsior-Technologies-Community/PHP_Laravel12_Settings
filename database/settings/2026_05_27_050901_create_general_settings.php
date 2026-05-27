<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.support_email', 'support@example.com');
        $this->migrator->add('general.phone_number', '+91 0000000000');
        $this->migrator->add('general.maintenance_message', 'We are currently upgrading our system. Please check back later.');
        $this->migrator->add('general.theme_color', '#2563eb'); 
        $this->migrator->add('general.site_logo', '');
    }
};