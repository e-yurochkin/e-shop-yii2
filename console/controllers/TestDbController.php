<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\db\Exception;

class TestDbController extends Controller
{
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->setDependency();
    }

    private function setDependency(): void
    {
        Yii::$container->set('console\controllers\RoleController');
        Yii::$container->set('console\controllers\UserController');
        Yii::$container->set('console\controllers\RbacController');
        Yii::$container->set('console\controllers\CategoryController');
        Yii::$container->set('console\controllers\ProductController');
        Yii::$container->set('console\controllers\CategoryProductController');
        Yii::$container->set('console\controllers\AttributeController');
        Yii::$container->set('console\controllers\CartController');
        Yii::$container->set('console\controllers\OrderController');
        Yii::$container->set('console\controllers\ReviewController');
        Yii::$container->set('console\controllers\DiscountController');
    }

    /**
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function actionInit(
        RoleController            $roleController,
        UserController            $userController,
        RbacController            $rbacController,
        CategoryController        $categoryController,
        ProductController         $productController,
        CategoryProductController $categoryProductController,
        AttributeController       $attributeController,
        CartController            $cartController,
        OrderController           $orderController,
        ReviewController          $reviewController,
        DiscountController        $discountController,
    ): void
    {
        $this->actionClearDb();
        Yii::$app->runAction('migrate');
        Yii::$app->runAction('migrate', ['migrationPath' => '@yii/rbac/migrations/']);

        $seeds = [
            $roleController,
//            $userController,
            $rbacController,
//            $categoryController,
//            $productController,
//            $categoryProductController,
//            $attributeController,
//            $cartController,
//            $orderController,
//            $reviewController,
//            $discountController,
        ];

        $this->actionSeed($seeds);
    }

    /**
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function actionSeed(array $seeds): void
    {
        foreach ($seeds as $seed) {
            $seed->actionInit();
        }
    }

    /**
     * @throws Exception
     */
    public function actionClearDb(): void
    {
        Yii::$app->getDb()->createCommand("SET foreign_key_checks = 0")->execute();

        foreach (\Yii::$app->db->schema->tableNames as $tableName) {
            Yii::$app->getDb()->createCommand()->dropTable($tableName)->execute();
        }

        Yii::$app->getDb()->createCommand("SET foreign_key_checks = 1")->execute();
    }
}