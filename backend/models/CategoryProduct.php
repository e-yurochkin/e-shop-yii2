<?php

namespace backend\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * CategoryProduct model
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $product_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class CategoryProduct extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%category_product}}';
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
