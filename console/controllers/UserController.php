<?php

namespace console\controllers;

use common\models\User;
use console\factories\SellerFactory;
use console\factories\UserFactory;
use yii\console\Controller;
use yii\db\Exception;

class UserController extends Controller
{
    private array $data = [
        [
            'username' => 'admin',
            'role_id' => 1,
            'email' => 'admin@gmail.com',
            'status' => 10,
        ],
        [
            'username' => 'seller',
            'role_id' => 2,
            'email' => 'seller@gmail.com',
            'status' => 10,
        ],
        [
            'username' => 'customer',
            'role_id' => 3,
            'email' => 'customer@gmail.com',
            'status' => 10,
        ],
    ];

    /**
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function actionInit(): void
    {
//        Yii::$app->db->createCommand()->truncateTable('user')->execute();

        foreach ($this->data as $item) {
            UserFactory::create(1, $item);
        }

        UserFactory::create(10);
        $this->initSellers();
    }

    /**
     * @throws Exception
     */
    private function initSellers(): void
    {
        foreach (User::find()->all() as $user) {
            if ($user->role_id === 2) {
                SellerFactory::create(1, [
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}