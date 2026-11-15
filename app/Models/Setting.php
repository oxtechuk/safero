<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Cache key for all settings array.
     */
    public const CACHE_KEY = 'safero_system_settings';

    /**
     * Retrieve a setting value by key with optional default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $all = static::allCached();

        if (array_key_exists($key, $all)) {
            $setting = $all[$key];

            return static::castValue($setting['value'], $setting['type'] ?? 'string');
        }

        return $default;
    }

    /**
     * Set or update a setting.
     */
    public static function set(string $key, mixed $value, ?string $group = null, ?string $type = null): void
    {
        $data = ['value' => is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : (string) $value];

        if ($group !== null) {
            $data['group'] = $group;
        }

        if ($type !== null) {
            $data['type'] = $type;
        }

        static::query()->updateOrCreate(['key' => $key], $data);

        static::clearCache();
    }

    /**
     * Get all settings grouped by group name.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function getAllGrouped(): array
    {
        $records = static::query()->orderBy('group')->orderBy('id')->get();
        $grouped = [];

        foreach ($records as $record) {
            $grouped[$record->group][$record->key] = [
                'id' => $record->id,
                'key' => $record->key,
                'value' => static::castValue($record->value, $record->type),
                'raw_value' => $record->value,
                'type' => $record->type,
                'description' => $record->description,
                'is_public' => $record->is_public,
            ];
        }

        return $grouped;
    }

    /**
     * Cache and return all settings as key => row array.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function allCached(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return static::query()
                ->get(['key', 'value', 'type', 'group'])
                ->keyBy('key')
                ->toArray();
        });
    }

    /**
     * Clear cached settings.
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Cast raw database value according to setting type.
     */
    protected static function castValue(?string $value, string $type): mixed
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'json' => json_decode($value, true) ?: [],
            default => $value,
        };
    }
}
