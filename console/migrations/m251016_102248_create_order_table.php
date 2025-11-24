<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order}}`.
 */
class m251016_102248_create_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('{{%order}}', [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->unsigned()->notNull(),
            'total_cost' => $this->integer()->unsigned()->defaultValue(0),
            'status_id' => $this->integer()->unsigned()->defaultValue(1),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ]);

        $this->createIndex(
            '{{%idx-order-user_id}}',
            '{{%order}}',
            'user_id'
        );

        $this->createIndex(
            '{{%idx-order-status_id}}',
            '{{%order}}',
            'status_id'
        );

        $this->addForeignKey(
            '{{%fk-order-user_id}}',
            '{{%order}}',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-order-status_id}}',
            '{{%order}}',
            'status_id',
            'order_status',
            'id',
            'CASCADE'
        );

        $this->createTable('{{%order_item}}', [
            'id' => $this->primaryKey()->unsigned(),
            'order_id' => $this->integer()->unsigned()->notNull(),
            'product_id' => $this->integer()->unsigned()->notNull(),
            'product_name' => $this->string()->notNull(),
            'price' => $this->integer()->unsigned()->notNull(),
            'quantity' => $this->integer()->unsigned()->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ]);

        $this->createIndex(
            '{{%idx-order_item-order_id}}',
            '{{%order_item}}',
            'order_id'
        );

        $this->createIndex(
            '{{%idx-order_item-product_id}}',
            '{{%order_item}}',
            'product_id'
        );

        $this->addForeignKey(
            '{{%fk-order_item-order_id}}',
            '{{%order_item}}',
            'order_id',
            'order',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-order_item-product_id}}',
            '{{%order_item}}',
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
        $this->dropTable('{{%order_item}}');
        $this->dropTable('{{%order}}');
    }
}
