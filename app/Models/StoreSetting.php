<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class StoreSetting extends Model
{
    /** @use HasFactory<\Database\Factories\StoreSettingFactory> */
    use HasFactory;

    protected $fillable = [
        'store_name',
        'whatsapp_number',
        'currency',
        'logo_path',
        'primary_color',
        'secondary_color',
        'button_color',
        'menu_color',
        'background_color',
        'text_color',
    ];

    public static function current(): self
    {
        return Cache::remember('store_settings_current', 3600, function () {
            return static::query()->firstOrCreate([], [
                'store_name' => config('app.name'),
                'whatsapp_number' => config('services.whatsapp.number', ''),
                'currency' => 'USD',
            ]);
        });
    }

    public static function flushCache(): void
    {
        Cache::forget('store_settings_current');
    }

    public static function brandCss(?self $setting = null): string
    {
        $setting ??= static::current();

        $map = [
            'primary_color' => '--brand-primary',
            'secondary_color' => '--brand-secondary',
            'button_color' => '--brand-button',
            'menu_color' => '--brand-menu',
            'background_color' => '--brand-background',
            'text_color' => '--brand-text',
        ];

        $parts = [];
        foreach ($map as $attribute => $variable) {
            $value = $setting->{$attribute} ?? '';
            if ($value !== null && $value !== '' && preg_match('/^#[0-9a-fA-F]{6}$/', $value)) {
                $parts[] = $variable . ': ' . $value . ';';
            }
        }

        // Also output derived admin tokens so they resolve at body level (not html)
        $brandVars = [];
        foreach ($map as $attribute => $variable) {
            $value = $setting->{$attribute} ?? '';
            if ($value !== null && $value !== '' && preg_match('/^#[0-9a-fA-F]{6}$/', $value)) {
                $brandVars[str_replace('--brand-', '', $variable)] = $value;
            }
        }

        if (!empty($brandVars)) {
            if (isset($brandVars['background'])) $parts[] = '--admin-bg: ' . $brandVars['background'] . ';';
            if (isset($brandVars['text'])) $parts[] = '--admin-text: ' . $brandVars['text'] . ';';
            if (isset($brandVars['menu'])) $parts[] = '--admin-sidebar: ' . $brandVars['menu'] . ';';
            if (isset($brandVars['primary'])) $parts[] = '--admin-primary: ' . $brandVars['primary'] . ';';
            if (isset($brandVars['secondary'])) $parts[] = '--admin-secondary: ' . $brandVars['secondary'] . ';';
        }

        return implode(' ', $parts);
    }
}
