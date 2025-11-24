<?php

namespace backend\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * OrderItem model
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $product_id
 * @property integer $product_name
 * @property integer $price
 * @property integer $quantity
 * @property integer $created_at
 * @property integer $updated_at
 */
class OrderItem extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%order_item}}';
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
