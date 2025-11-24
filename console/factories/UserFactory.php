<?php

namespace console\factories;

use common\models\User;
use Yii;
use yii\base\Exception;


class UserFactory extends Factory
{
    protected static string $modelClass = User::class;
    protected static array $allowedKeys = [
        'username',
        'role_id',
        'auth_key',
        'expires_at',
        'password_hash',
        'password_reset_token',
        'email',
        'status',
        'created_at',
        'updated_at',
    ];

    /**
     * Define the model's default state.
     * @throws Exception
     */
    protected static function definition(): array
    {
        return [
            'username' => self::$faker->unique()->userName,
            'role_id' => self::$faker->numberBetween(2, 3),
            'auth_key' => null,
            'expires_at' => null,
            'password_hash' => Yii::$app->security->generatePasswordHash(Yii::$app->params['userFactoryPassword']),
            'password_reset_token' => null,
            'email' => self::$faker->unique()->email,
            'status' => 10,
            'created_at' => time(),
            'updated_at' => time(),
        ];
    }
}
