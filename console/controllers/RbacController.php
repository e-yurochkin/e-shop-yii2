<?php

namespace console\controllers;

use common\models\User;
use Yii;
use yii\base\Exception;
use yii\base\UserException;
use yii\console\Controller;

class RbacController extends Controller
{
    private array $adminPermissions = [];
    private array $sellerPermissions = [];
    private array $customerPermissions = [];

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function actionInit(): void
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // КАТЕГОРИИ
        // разрешение на создание новой категории (только admin)
        $createCategory = $auth->createPermission('createCategory');
        $createCategory->description = 'Create category';
        $auth->add($createCategory);
        $this->adminPermissions[] = $createCategory;

        // разрешение на изменение любой категории (только admin)
        $editCategory = $auth->createPermission('editCategory');
        $editCategory->description = 'Edite any category';
        $auth->add($editCategory);
        $this->adminPermissions[] = $editCategory;

        // разрешение на удаление любой категории (только admin)
        $deleteCategory = $auth->createPermission('deleteCategory');
        $deleteCategory->description = 'Delete any category';
        $auth->add($deleteCategory);
        $this->adminPermissions[] = $deleteCategory;


        // ПРОДУКТЫ
        // разрешение на создание нового продукта (admin и seller)
        $createProduct = $auth->createPermission('createProduct');
        $createProduct->description = 'Create product';
        $auth->add($createProduct);
        $this->sellerPermissions[] = $createProduct;

        // разрешение на изменение любого продукта (только admin)
        $editProduct = $auth->createPermission('editProduct');
        $editProduct->description = 'Edit any product';
        $auth->add($editProduct);
        $this->adminPermissions[] = $editProduct;

        // разрешение на удаление любого продукта (только admin)
        $deleteProduct = $auth->createPermission('deleteProduct');
        $deleteProduct->description = 'Delete any product';
        $auth->add($deleteProduct);
        $this->adminPermissions[] = $deleteProduct;

        // разрешение на изменение своего продукта (admin и seller)
        $editOwnProduct = $auth->createPermission('editOwnProduct');
        $editOwnProduct->description = 'Edit own product';
        $auth->add($editOwnProduct);
        $this->sellerPermissions[] = $editOwnProduct;

        // разрешение на удаление своего продукта (admin и seller)
        $deleteOwnProduct = $auth->createPermission('deleteOwnProduct');
        $deleteOwnProduct->description = 'Delete own product';
        $auth->add($deleteOwnProduct);
        $this->sellerPermissions[] = $deleteOwnProduct;


        // АТРИБУТЫ
        // разрешение на просмотр атрибутов (admin и seller)
        $viewAttribute = $auth->createPermission('viewAttributes');
        $viewAttribute->description = 'View attributes';
        $auth->add($viewAttribute);
        $this->sellerPermissions[] = $viewAttribute;

        // разрешение на создание нового атрибута (только admin)
        $createAttribute = $auth->createPermission('createAttribute');
        $createAttribute->description = 'Create attribute';
        $auth->add($createAttribute);
        $this->adminPermissions[] = $createAttribute;

        // разрешение на изменение атрибута (только admin)
        $editAttribute = $auth->createPermission('editAttribute');
        $editAttribute->description = 'Edit any attribute';
        $auth->add($editAttribute);
        $this->adminPermissions[] = $editAttribute;

        // разрешение на удаление атрибута (только admin)
        $deleteAttribute = $auth->createPermission('deleteAttribute');
        $deleteAttribute->description = 'Delete any attribute';
        $auth->add($deleteAttribute);
        $this->adminPermissions[] = $deleteAttribute;

        // РОЛИ
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $seller = $auth->createRole('seller');
        $auth->add($seller);
        $customer = $auth->createRole('customer');
        $auth->add($customer);

        // назначаем разрешения роли $seller
        foreach ($this->sellerPermissions as $sellerPermission) {
            $auth->addChild($seller, $sellerPermission);
        }

        // назначаем роли $admin все разрешения $seller
        $auth->addChild($admin, $seller);

        // назначаем разрешения роли $admin
        foreach ($this->adminPermissions as $adminPermission) {
            $auth->addChild($admin, $adminPermission);
        }

        // назначаем роли конкретным пользователям из БД
//        $auth->assign($admin, 1);
//        $auth->assign($seller, 2);
//        $auth->assign($customer, 3);

        // назначение ролей всем созданным пользователям
        foreach (User::find()->all() as $user) {
            match ($user->role_id) {
                1 => $auth->assign($admin, $user->id),
                2 => $auth->assign($seller, $user->id),
                3 => $auth->assign($customer, $user->id),
                default => throw new UserException('Invalid user role'),
            };
        }
    }
}