<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait HasCustomId
{
    /**
     * Boot the trait - automatically called by Eloquent
     */
    protected static function bootHasCustomId(): void
    {
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = $model->generateCustomId();
            }
        });
    }

    /**
     * Initialize the trait - set incrementing and keyType
     */
    public function initializeHasCustomId(): void
    {
        $this->incrementing = false;
        $this->keyType = 'string';
    }

    /**
     * Get the custom ID prefix for this model
     */
    abstract public static function getIdPrefix(): string;

    /**
     * Get the number of digits for the ID (excluding prefix)
     */
    public static function getIdDigits(): int
    {
        return 5; // Default 5 digits, can be overridden
    }

    /**
     * Generate a new custom ID
     */
    public function generateCustomId(): string
    {
        $prefix = static::getIdPrefix();
        $digits = static::getIdDigits();
        $table = $this->getTable();
        $keyName = $this->getKeyName();

        // Get the last ID from the database
        $lastRecord = DB::table($table)
            ->where($keyName, 'LIKE', $prefix . '%')
            ->orderByRaw("CAST(SUBSTRING({$keyName}, " . (strlen($prefix) + 1) . ") AS UNSIGNED) DESC")
            ->first();

        if ($lastRecord) {
            // Extract the number from the last ID
            $lastId = $lastRecord->{$keyName};
            $lastNumber = (int) substr($lastId, strlen($prefix));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Format the new ID with zero-padding
        return $prefix . str_pad($newNumber, $digits, '0', STR_PAD_LEFT);
    }

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public function getIncrementing(): bool
    {
        return false;
    }

    /**
     * Get the data type for the primary key.
     */
    public function getKeyType(): string
    {
        return 'string';
    }
}
