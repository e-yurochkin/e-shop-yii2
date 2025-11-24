<?php

namespace backend\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Cart model
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $total_cost
 * @property integer $created_at
 * @property integer $updated_at
 */
class Cart extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%cart}}';
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

    public function getCartItems(): ActiveQuery
    {
        return $this->hasMany(CartItem::class, ['cart_id' => 'id']);
    }
}
