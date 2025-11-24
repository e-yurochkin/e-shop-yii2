<?php

namespace backend\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * AttributeValue model
 *
 * @property integer $id
 * @property integer $attribute_id
 * @property integer product_id
 * @property string $value
 * @property integer $created_at
 * @property integer $updated_at
 */
class AttributeValue extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%attribute_value}}';
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
