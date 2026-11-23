<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public const DEFAULTS = [
        'documentation_font_family' => 'sans',
        'documentation_font_size' => '16',
        'documentation_text_color' => '#0a1420',
        'documentation_heading_color' => '#003366',
        'documentation_background_color' => '#ffffff',
        'documentation_line_height' => '1.6',
    ];

    public static function documentationStyle(): array
    {
        if (! Schema::hasTable('settings')) {
            return self::DEFAULTS;
        }

        $stored = self::query()->pluck('value', 'key')->all();

        return array_merge(self::DEFAULTS, $stored);
    }
}
