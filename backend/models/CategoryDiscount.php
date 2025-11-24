<?php

namespace backend\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Attribute model
 *
 * @property integer $id
 * @property integer $discount_id
 * @property integer $category_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class CategoryDiscount extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%category_discount}}';
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
