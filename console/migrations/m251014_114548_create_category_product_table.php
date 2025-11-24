<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%category_product}}`.
 */
class m251014_114548_create_category_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('{{%category_product}}', [
            'id' => $this->primaryKey()->unsigned(),
            'category_id' => $this->integer()->unsigned()->notNull(),
            'product_id' => $this->integer()->unsigned()->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ]);

        $this->createIndex(
            '{{%idx-category_product-product_id}}',
            '{{%category_product}}',
            'product_id'
        );

        $this->addForeignKey(
            '{{%fk-category_product-category_id}}',
            '{{%category_product}}',
            'category_id',
            'category',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-category_product-product_id}}',
            '{{%category_product}}',
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
        $this->dropTable('{{%category_product}}');
    }
}
