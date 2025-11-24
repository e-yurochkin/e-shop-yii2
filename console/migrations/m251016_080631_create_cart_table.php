<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cart}}`.
 */
class m251016_080631_create_cart_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('{{%cart}}', [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->unsigned()->notNull(),
            'total_cost' => $this->integer()->unsigned()->defaultValue(0),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ]);

        $this->createIndex(
            '{{%idx-cart-user_id}}',
            '{{%cart}}',
            'user_id'
        );

        $this->addForeignKey(
            '{{%fk-cart-user_id}}',
            '{{%cart}}',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->createTable('{{%cart_item}}', [
            'id' => $this->primaryKey()->unsigned(),
            'cart_id' => $this->integer()->unsigned()->notNull(),
            'product_id' => $this->integer()->unsigned()->notNull(),
            'quantity' => $this->integer()->unsigned()->defaultValue(0),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ]);

        $this->createIndex(
            '{{%idx-cart_item-cart_id}}',
            '{{%cart_item}}',
            'cart_id'
        );

        $this->createIndex(
            '{{%idx-cart_item-product_id}}',
            '{{%cart_item}}',
            'product_id'
        );

        $this->addForeignKey(
            '{{%fk-cart_item-cart_id}}',
            '{{%cart_item}}',
            'cart_id',
            'cart',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-cart_item-product_id}}',
            '{{%cart_item}}',
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
        $this->dropTable('{{%cart_item}}');
        $this->dropTable('{{%cart}}');
    }
}
