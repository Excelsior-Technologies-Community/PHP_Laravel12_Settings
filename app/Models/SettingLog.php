<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingLog extends Model
{
    protected $fillable = [
        'site_name_old',
        'site_name_new',
        'status_old',
        'status_new',
    ];
}