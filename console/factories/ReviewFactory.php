<?php

namespace console\factories;

use backend\models\Review;


class ReviewFactory extends Factory
{
    protected static string $modelClass = Review::class;
    protected static array $allowedKeys = [
        'user_id',
        'product_id',
        'review',
        'created_at',
        'updated_at',
    ];

    /**
     * Define the model's default state.
     */
    protected static function definition(): array
    {
        return [
            'user_id' => 1,
            'product_id' => 1,
            'review' => self::$faker->text(),
            'created_at' => time(),
            'updated_at' => time(),
        ];
    }
}
