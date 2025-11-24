<?php

namespace console\controllers;

use console\factories\ProductFactory;
use yii\console\Controller;
use yii\db\Exception;

class ProductController extends Controller
{
    /**
     * @throws Exception
     */
    public function actionInit(): void
    {
        ProductFactory::create(100);
    }
}
