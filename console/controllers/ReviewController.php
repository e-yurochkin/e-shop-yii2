<?php

namespace console\controllers;

use backend\models\Product;
use common\models\User;
use console\factories\ReviewFactory;
use yii\console\Controller;
use yii\db\Exception;

class ReviewController extends Controller
{
    /**
     * @throws Exception
     */
    public function actionInit(): void
    {
        $usersCount = count(User::find()->all());

        foreach (Product::find()->all() as $product) {
            for ($i = 1; $i <= rand(1, 3); $i++) {
                ReviewFactory::create(1, [
                    'user_id' => rand(1, $usersCount),
                    'product_id' => $product->id,
                ]);
            }
        }
    }
}
