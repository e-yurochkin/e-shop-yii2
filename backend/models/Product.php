<?php

namespace backend\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Product model
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $manufacturer
 * @property string $manufacturer_country
 * @property integer $price
 * @property integer $stock_quantity
 * @property string $description
 * @property string $characteristic
 * @property string $image
 * @property integer $created_at
 * @property integer $updated_at
 *
 */
class Product extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%product}}';
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
