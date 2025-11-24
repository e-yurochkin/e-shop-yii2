<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product}}`.
 */
class m251010_134928_create_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string()->unique()->notNull(),
            'slug' => $this->string()->unique()->notNull(),
            'manufacturer' => $this->string(127)->notNull(),
            'manufacturer_country' => $this->string(63)->notNull(),
            'price' => $this->integer()->unsigned()->notNull(),
            'stock_quantity' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'description' => $this->text(),
            'characteristic' => $this->text(),
            'image' => $this->string(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable('{{%product}}');
    }
}
