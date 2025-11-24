<?php

namespace backend\requests\auth;

use backend\models\Seller;
use common\models\User;
use Yii;
use yii\base\Exception;
use yii\base\Model;

/**
 * Signup request
 */
class RegisterSellerDataRequest extends Model
{
    public ?string $username = null;
    public ?string $shop_name = null;
    public ?string $legal_address = null;
    public ?string $physical_address = null;
    public ?string $inn = null;
    public ?string $ogrn = null;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            [
                'username',
                'exist',
                'targetClass' => '\common\models\User',
                'message' => 'There is no user with this name.'
            ],

            ['shop_name', 'trim'],
            ['shop_name', 'required'],
            ['shop_name', 'string', 'min' => 2, 'max' => 255],
            [
                'shop_name',
                'unique',
                'targetClass' => '\backend\models\Seller',
                'message' => 'This shop name has already been taken.'
            ],

            ['legal_address', 'trim'],
            ['legal_address', 'required'],
            ['legal_address', 'string', 'min' => 2, 'max' => 255],
            [
                'legal_address',
                'unique',
                'targetClass' => '\backend\models\Seller',
                'message' => 'This legal address has already been taken.'
            ],

            ['physical_address', 'trim'],
            ['physical_address', 'required'],
            ['physical_address', 'string', 'min' => 2, 'max' => 255],
            [
                'physical_address',
                'unique',
                'targetClass' => '\backend\models\Seller',
                'message' => 'This physical address has already been taken.'
            ],

            ['inn', 'trim'],
            ['inn', 'required'],
            ['inn', 'string', 'min' => 2, 'max' => 255],
            [
                'inn',
                'unique',
                'targetClass' => '\backend\models\Seller',
                'message' => 'This inn has already been taken.'
            ],

            ['ogrn', 'trim'],
            ['ogrn', 'required'],
            ['ogrn', 'string', 'min' => 2, 'max' => 255],
            [
                'ogrn',
                'unique',
                'targetClass' => '\backend\models\Seller',
                'message' => 'This inn has already been taken.'
            ],
        ];
    }

    /**
     * Register seller info.
     *
     * @return array whether the creating new seller was successful
     * @throws Exception
     * @throws \Exception
     */
    public function register(array $data): array
    {
        Yii::$app->response->statusCode = 400;

        if (!$this->load($data, '') || !$this->validate()) {
            return ['errors' => $this->errors];
        }

        $user = User::findOne(['username' => $this->username]);

        if ($user->getRole()->one()->name !== 'seller') {
            return ['message' => 'This user ' . $this->username . ' is not a seller.'];
        }

        $seller = new Seller();
        $seller->user_id = $user->id;
        $seller->shop_name = $this->shop_name;
        $seller->legal_address = $this->legal_address;
        $seller->physical_address = $this->physical_address;
        $seller->inn = $this->inn;
        $seller->ogrn = $this->ogrn;
        $seller->save();

        Yii::$app->response->statusCode = 201;

        return ['message' => 'Successful registered seller data.'];
    }
}
