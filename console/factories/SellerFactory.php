<?php

namespace console\factories;

use backend\models\Seller;

class SellerFactory extends Factory
{
    protected static string $modelClass = Seller::class;
    protected static array $allowedKeys = [
        'user_id',
        'shop_name',
        'legal_address',
        'physical_address',
        'inn',
        'ogrn',
        'created_at',
        'updated_at',
    ];

    /**
     * Define the model's default state.
     */
    protected static function definition(): array
    {
        return [
            'user_id' => 2,
            'shop_name' => self::$faker->unique()->company,
            'legal_address' => self::$faker->address,
            'physical_address' => self::$faker->address,
//            'inn' => rand(1000000000, 9999999999),
            'inn' => self::$faker->unique()->randomNumber(8, true),
//            'ogrn' => rand(1000000000000, 9999999999999),
            'ogrn' => self::$faker->unique()->randomNumber(9, true),
            'created_at' => time(),
            'updated_at' => time(),
        ];
    }
}
