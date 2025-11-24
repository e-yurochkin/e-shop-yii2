<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%attribute}}`.
 */
class m251014_132519_create_attribute_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('{{%attribute}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string()->unique()->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ]);

        $this->createTable('{{%attribute_value}}', [
            'id' => $this->primaryKey()->unsigned(),
            'attribute_id' => $this->integer()->unsigned()->notNull(),
            'product_id' => $this->integer()->unsigned()->notNull(),
            'value' => $this->string()->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ]);

        $this->createIndex(
            '{{%idx-attribute_value-attribute_id}}',
            '{{%attribute_value}}',
            'attribute_id'
        );

        $this->createIndex(
            '{{%idx-attribute_value-product_id}}',
            '{{%attribute_value}}',
            'product_id'
        );

        $this->addForeignKey(
            '{{%fk-attribute_value-attribute_id}}',
            '{{%attribute_value}}',
            'attribute_id',
            'attribute',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-attribute_value-product_id}}',
            '{{%attribute_value}}',
            'product_id',
            'product',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable('{{%attribute_value}}');
        $this->dropTable('{{%attribute}}');
    }
}
