<?php

namespace console\factories;

use backend\models\Discount;


class DiscountFactory extends Factory
{
    protected static string $modelClass = Discount::class;
    protected static array $allowedKeys = [
        'name',
        'description',
        'value',
        'min_cart_value',
        'starts_at',
        'ends_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Define the model's default state.
     */
    protected static function definition(): array
    {
        return [
            'name' => self::$faker->unique()->sentence(5),
            'description' => self::$faker->text(),
            'value' => self::$faker->numberBetween(5, 50),
            'min_cart_value' => self::$faker->numberBetween(1000, 10000),
            'starts_at' => time(),
            'ends_at' => time() + 2592000,
            'created_at' => time(),
            'updated_at' => time(),
        ];
    }
}
