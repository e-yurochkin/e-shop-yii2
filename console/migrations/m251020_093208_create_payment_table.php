<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payment}}`.
 */
class m251020_093208_create_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('{{%payment}}', [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->unsigned()->notNull(),
            'order_id' => $this->integer()->unsigned()->notNull(),
            'payment_code' => $this->string(127)->notNull(), // айди платежа в юкассе
            'total_cost' => $this->integer()->unsigned()->notNull(),
            'status' => $this->string(63)->notNull(),
            'description' => $this->text()->notNull(),
        ]);

        $this->createIndex(
            '{{%idx-payment-user_id}}',
            '{{%payment}}',
            'user_id'
        );

        $this->createIndex(
            '{{%idx-payment-order_id}}',
            '{{%payment}}',
            'order_id'
        );

        $this->addForeignKey(
            '{{%fk-payment-user_id}}',
            '{{%payment}}',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-payment-order_id}}',
            '{{%payment}}',
            'order_id',
            'order',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable('{{%payment}}');
    }
}
