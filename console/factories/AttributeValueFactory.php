<?php

namespace console\factories;

use backend\models\AttributeValue;


class AttributeValueFactory extends Factory
{
    protected static string $modelClass = AttributeValue::class;
    protected static array $allowedKeys = [
        'attribute_id',
        'product_id',
        'value',
        'created_at',
        'updated_at',
    ];

    /**
     * Define the model's default state.
     */
    protected static function definition(): array
    {
        return [
            'attribute_id' => 1,
            'product_id' => 1,
            'value' => self::$faker->word,
            'created_at' => time(),
            'updated_at' => time(),
        ];
    }
}
