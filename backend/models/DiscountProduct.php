<?php

namespace backend\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Attribute model
 *
 * @property integer $id
 * @property integer $discount_id
 * @property integer $product_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class DiscountProduct extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%discount_product}}';
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
