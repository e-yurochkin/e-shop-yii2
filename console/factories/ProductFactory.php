<?php

namespace console\factories;

use backend\models\Product;


class ProductFactory extends Factory
{
    protected static string $modelClass = Product::class;
    private static array $allowedKeys = [
        'name',
        'slug',
        'manufacturer',
        'manufacturer_country',
        'price',
        'stock_quantity',
        'description',
        'characteristic',
        'image',
        'created_at',
        'updated_at',
    ];

    protected static function definition(): array
    {
        return [
            'name' => self::$faker->unique()->sentence(4),
            'slug' => self::$faker->unique()->slug,
            'manufacturer' => self::$faker->company,
            'manufacturer_country' => self::$faker->country,
            'price' => self::$faker->randomNumber(5),
            'stock_quantity' => self::$faker->randomNumber(3),
            'description' => self::$faker->text,
            'characteristic' => self::$faker->text,
            'image' => self::$faker->unique()->imageUrl(),
            'created_at' => time(),
            'updated_at' => time(),
        ];
    }
}
