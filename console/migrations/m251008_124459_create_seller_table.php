<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%seller}}`.
 */
class m251008_124459_create_seller_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('{{%seller}}', [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->unsigned()->unique()->notNull(),
            'shop_name' => $this->string()->unique()->notNull(),
            'legal_address' => $this->string()->notNull(),
            'physical_address' => $this->string()->notNull(),
            'inn' => $this->string(31)->unique()->notNull(),
            'ogrn' => $this->string(31)->unique()->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ]);

        $this->createIndex(
            '{{%idx-seller-user_id}}',
            '{{%seller}}',
            'user_id'
        );

        $this->addForeignKey(
            '{{%fk-seller-user_id}}',
            '{{%seller}}',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable('{{%seller}}');
    }
}
