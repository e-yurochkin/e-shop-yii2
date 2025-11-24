<?php

namespace backend\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Attribute model
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $value
 * @property integer $min_cart_value
 * @property string $starts_at
 * @property string $ends_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class Discount extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%discount}}';
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
