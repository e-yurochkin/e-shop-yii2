<?php

namespace console\factories;

use Faker\Factory as FakerFactory;
use Faker\Generator;
use yii\db\Exception;


abstract class Factory
{
    protected static Generator $faker;

    /**
     * @throws Exception
     */
    public static function create(int $quantity = 1, array $data = []): void
    {
        self::init();

        while ($quantity > 0) {
            self::prepareData($data);
            $quantity--;
        }
    }

    private static function init(): void
    {
        self::$faker = FakerFactory::create();
    }

    /**
     * @throws Exception
     */
    private static function prepareData(array $data): void
    {
        $defaultData = static::definition();

        if ($data) {
            $errors = [];

            foreach ($data as $key => $value) {
                if (!in_array($key, static::$allowedKeys)) {
                    $errors[] = 'Key: ' . $key . ' is not allowed.';
                }
            }

            if (!empty($errors)) {
                throw new Exception(implode(' ', $errors));
            }

            $defaultData = array_replace($defaultData, $data);
        }

        self::make($defaultData);
    }

    protected static function make(array $data): void
    {
        $model = new static::$modelClass($data);
        $model->save();
    }
}
