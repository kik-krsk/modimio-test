<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user_agent}}`.
 */
class m220413_081925_create_user_agent_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_agent}}', [
            'id' => $this->primaryKey(),
            'full' => $this->text()->notNull(),
            'os' => $this->string(),
            'arch' => $this->string(),
            'browser' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_agent}}');
    }
}
