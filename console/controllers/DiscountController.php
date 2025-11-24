<?php

namespace console\controllers;

use backend\models\CategoryDiscount;
use backend\models\Discount;
use backend\models\DiscountProduct;
use console\factories\DiscountFactory;
use yii\console\Controller;
use yii\db\Exception;

class DiscountController extends Controller
{
    /**
     * @throws Exception
     */
    public function actionInit(): void
    {
        $discountQty = 5;
        DiscountFactory::create($discountQty);

        foreach (Discount::find()->all() as $discount) {
            $categoryData = [
                'discount_id' => $discount->id,
                'category_id' => rand(11, 30),
            ];

            $categoryDiscount = new CategoryDiscount($categoryData);
            $categoryDiscount->save();

            $productData = [
                'discount_id' => $discount->id,
                'product_id' => rand(1, 30),
            ];

            $discountProduct = new DiscountProduct($productData);
            $discountProduct->save();
        }
    }
}
