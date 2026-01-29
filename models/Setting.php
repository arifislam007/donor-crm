<?php
/**
 * Setting Model
 * NGO Donor Management System
 * For storing application settings including payment gateway configurations
 */

class Setting extends Model
{
    protected static string $table = 'settings';
    
    /**
     * Get a setting value by key
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = self::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }
        
        return self::castValue($setting->value, $setting->type);
    }
    
    /**
     * Set a setting value
     */
    public static function set(string $key, mixed $value, string $type = 'string'): self
    {
        $existing = self::where('key', $key)->first();
        
        $data = [
            'key' => $key,
            'value' => self::encodeValue($value, $type),
            'type' => $type
        ];
        
        if ($existing) {
            $existing->update($data);
            return $existing;
        }
        
        return self::create($data);
    }
    
    /**
     * Get all payment gateway settings
     */
    public static function getPaymentSettings(): array
    {
        return [
            'mode' => self::get('payment_mode', 'sandbox'),
            'sslcommerz' => [
                'store_id' => self::get('sslcommerz_store_id', ''),
                'store_password' => self::get('sslcommerz_store_password', ''),
                'sandbox' => self::get('sslcommerz_sandbox', true),
            ],
            'nagad' => [
                'merchant_id' => self::get('nagad_merchant_id', ''),
                'merchant_number' => self::get('nagad_merchant_number', ''),
                'sandbox' => self::get('nagad_sandbox', true),
            ],
            'bkash' => [
                'app_key' => self::get('bkash_app_key', ''),
                'app_secret' => self::get('bkash_app_secret', ''),
                'username' => self::get('bkash_username', ''),
                'password' => self::get('bkash_password', ''),
                'sandbox' => self::get('bkash_sandbox', true),
            ],
            'rocket' => [
                'merchant_id' => self::get('rocket_merchant_id', ''),
                'merchant_number' => self::get('rocket_merchant_number', ''),
                'sandbox' => self::get('rocket_sandbox', true),
            ],
        ];
    }
    
    /**
     * Check if sandbox mode is enabled for a gateway
     */
    public static function isSandbox(string $gateway): bool
    {
        return match($gateway) {
            'sslcommerz' => self::get('sslcommerz_sandbox', true),
            'nagad' => self::get('nagad_sandbox', true),
            'bkash' => self::get('bkash_sandbox', true),
            'rocket' => self::get('rocket_sandbox', true),
            default => true
        };
    }
    
    /**
     * Get all settings as key-value pairs
     */
    public static function all(): array
    {
        $settings = parent::all();
        $result = [];
        
        foreach ($settings as $setting) {
            $result[$setting->key] = self::castValue($setting->value, $setting->type);
        }
        
        return $result;
    }
    
    /**
     * Cast value based on type
     */
    private static function castValue(string $value, string $type): mixed
    {
        return match($type) {
            'integer' => (int) $value,
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($value, true),
            default => $value
        };
    }
    
    /**
     * Encode value based on type
     */
    private static function encodeValue(mixed $value, string $type): string
    {
        return match($type) {
            'boolean' => $value ? '1' : '0',
            'json' => json_encode($value),
            default => (string) $value
        };
    }
}
