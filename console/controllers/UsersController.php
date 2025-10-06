<?php

namespace console\controllers;

use common\models\User;
use Yii;
use yii\console\Controller;

class UsersController extends Controller
{
    public function actionInit(): void
    {
        User::deleteAll();

        $admin = new User();
        $admin->id = 1;
        $admin->username = 'admin';
        $admin->email = 'admin@gmail.com';
        $admin->setPassword('dron');
//        $admin->generateAuthKey();
        $admin->generateEmailVerificationToken();
        $admin->status = User::STATUS_ACTIVE;
        $admin->save();


        $seller = new User();
        $seller->id = 2;
        $seller->username = 'seller';
        $seller->email = 'seller@gmail.com';
        $seller->setPassword('dron');
//        $seller->generateAuthKey();
        $seller->generateEmailVerificationToken();
        $seller->status = User::STATUS_ACTIVE;
        $seller->save();

        $customer = new User();
        $customer->id = 3;
        $customer->username = 'customer';
        $customer->email = 'customer@gmail.com';
        $customer->setPassword('dron');
//        $customer->generateAuthKey();
        $customer->generateEmailVerificationToken();
        $customer->status = User::STATUS_ACTIVE;
        $customer->save();
    }
}