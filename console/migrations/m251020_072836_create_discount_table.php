<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%discount}}`.
 */
class m251020_072836_create_discount_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('{{%discount}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string()->unique()->notNull(),
            'description' => $this->string(),
            'value' => $this->integer()->unsigned()->notNull(),
            'min_cart_value' => $this->integer()->unsigned()->defaultValue(0),
            'starts_at' => $this->integer()->unsigned(),
            'ends_at' => $this->integer()->unsigned(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ]);

        $this->createTable('{{%category_discount}}', [
            'id' => $this->primaryKey()->unsigned(),
            'discount_id' => $this->integer()->unsigned()->notNull(),
            'category_id' => $this->integer()->unsigned()->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ]);

        $this->createIndex(
            '{{%idx-category_discount-discount_id}}',
            '{{%category_discount}}',
            'discount_id'
        );

        $this->createIndex(
            '{{%idx-category_discount-category_id}}',
            '{{%category_discount}}',
            'category_id'
        );

        $this->addForeignKey(
            '{{%fk-category_discount-discount_id}}',
            '{{%category_discount}}',
            'discount_id',
            'discount',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-category_discount-category_id}}',
            '{{%category_discount}}',
            'category_id',
            'category',
            'id',
            'CASCADE'
        );

        $this->createTable('{{%discount_product}}', [
            'id' => $this->primaryKey()->unsigned(),
            'discount_id' => $this->integer()->unsigned()->notNull(),
            'product_id' => $this->integer()->unsigned()->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ]);

        $this->createIndex(
            '{{%idx-discount_product-discount_id}}',
            '{{%discount_product}}',
            'discount_id'
        );

        $this->createIndex(
            '{{%idx-discount_product-product_id}}',
            '{{%discount_product}}',
            'product_id'
        );

        $this->addForeignKey(
            '{{%fk-discount_product-discount_id}}',
            '{{%discount_product}}',
            'discount_id',
            'discount',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-discount_product-product_id}}',
            '{{%discount_product}}',
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
        $this->dropTable('{{%discount_product}}');
        $this->dropTable('{{%category_discount}}');
        $this->dropTable('{{%discount}}');
    }
}
