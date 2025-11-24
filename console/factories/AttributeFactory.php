<?php

namespace console\factories;

use backend\models\Attribute;


class AttributeFactory extends Factory
{
    protected static string $modelClass = Attribute::class;
    protected static array $allowedKeys = [
        'name',
        'created_at',
        'updated_at',
    ];

    /**
     * Define the model's default state.
     */
    protected static function definition(): array
    {
        return [
            'name' => self::$faker->unique()->sentence(1),
            'created_at' => time(),
            'updated_at' => time(),
        ];
    }
}
