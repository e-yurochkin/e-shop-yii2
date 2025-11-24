<?php

namespace console\controllers;

use backend\models\Role;
use yii\console\Controller;
use yii\db\Exception;

class RoleController extends Controller
{
    private array $roles = [
        'admin',
        'seller',
        'customer',
    ];

    /**
     * @throws Exception
     */
    public function actionInit(): void
    {
//        Yii::$app->db->createCommand()->truncateTable('role')->execute();

        foreach ($this->roles as $role) {
            $model = new Role();
            $model->name = $role;
            $model->save();
        }
    }
}