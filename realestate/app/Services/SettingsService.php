<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    protected const FILE_PATH = 'settings.json';
    protected const CACHE_KEY = 'site_settings';

    /**
     * Get all settings.
     */
    public function all(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            if (Storage::exists(self::FILE_PATH)) {
                $content = Storage::get(self::FILE_PATH);
                return json_decode($content, true) ?? [];
            }
            return [];
        });
    }

    /**
     * Get a specific setting by key.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $settings = $this->all();
        return $settings[$key] ?? $default;
    }

    /**
     * Set a specific setting.
     */
    public function set(string $key, mixed $value): void
    {
        $settings = $this->all();
        $settings[$key] = $value;
        $this->save($settings);
    }

    /**
     * Set multiple settings.
     */
    public function setMultiple(array $values): void
    {
        $settings = $this->all();
        $settings = array_merge($settings, $values);
        $this->save($settings);
    }

    /**
     * Save settings to file and clear cache.
     */
    protected function save(array $settings): void
    {
        Storage::put(self::FILE_PATH, json_encode($settings, JSON_PRETTY_PRINT));
        Cache::forget(self::CACHE_KEY);
    }
}
