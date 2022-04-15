<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%log}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user_agent}}`
 */
class m220413_082337_create_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%log}}', [
            'id' => $this->primaryKey(),
            'ip' => $this->string()->notNull(),
            'date' => $this->datetime()->notNull(),
            'url' => $this->text()->notNull(),
            'user_agent_id' => $this->integer(),
        ]);

        // creates index for column `user_agent_id`
        $this->createIndex(
            '{{%idx-log-user_agent_id}}',
            '{{%log}}',
            'user_agent_id'
        );

        // add foreign key for table `{{%user_agent}}`
        $this->addForeignKey(
            '{{%fk-log-user_agent_id}}',
            '{{%log}}',
            'user_agent_id',
            '{{%user_agent}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user_agent}}`
        $this->dropForeignKey(
            '{{%fk-log-user_agent_id}}',
            '{{%log}}'
        );

        // drops index for column `user_agent_id`
        $this->dropIndex(
            '{{%idx-log-user_agent_id}}',
            '{{%log}}'
        );

        $this->dropTable('{{%log}}');
    }
}
