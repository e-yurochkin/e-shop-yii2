<?php

namespace console\controllers;

use backend\models\CategoryProduct;
use backend\models\Product;
use Faker\Factory;
use yii\console\Controller;
use yii\db\Exception;

class CategoryProductController extends Controller
{
    /**
     * @throws Exception
     */
    public function actionInit(): void
    {
        $faker = Factory::create();

        foreach (Product::find()->all() as $product) {
            $categoryProduct = new CategoryProduct([
                'category_id' => $faker->numberBetween(11, 30),
                'product_id' => $product->id
            ]);
            $categoryProduct->save();
        }
    }
}
