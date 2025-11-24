<?php

namespace console\controllers;

use backend\models\Product;
use console\factories\AttributeFactory;
use console\factories\AttributeValueFactory;
use yii\console\Controller;
use yii\db\Exception;

class AttributeController extends Controller
{
    /**
     * @throws Exception
     */
    public function actionInit(): void
    {
        $attributeQty = 100;
        AttributeFactory::create($attributeQty);

        foreach (Product::find()->all() as $product) {
            $data = [
                'attribute_id' => rand(1, $attributeQty),
                'product_id' => $product->id,
            ];

            AttributeValueFactory::create(1, $data);
        }
    }
}
