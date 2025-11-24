<?php

namespace backend\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Seller model
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $shop_name
 * @property string $legal_address
 * @property string $physical_address
 * @property string $inn
 * @property string $ogrn
 * @property integer $created_at
 * @property integer $updated_at
 */
class Seller extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%seller}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }
}
