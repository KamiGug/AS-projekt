<?php

use yii\db\Migration;

/**
 * Class m250129_160235_add_ban_table
 */
class m250129_160235_add_ban_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('Ban', [
            'id' => $this->primaryKey(),
            'id_user' => $this->integer()->notNull(),
            'type' => $this->string(15)->notNull(),
            'since' => $this->dateTime()->notNull(),
            'until' => $this->dateTime()->notNull(),
            'reason' => $this->string(50),
            'issued_by' => $this->integer(),
        ]);
        $this->addForeignKey(
            'fk_ban_id_user',
            'Ban',
            'id_user',
            'User',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_ban_issued_by',
            'Ban',
            'issued_by',
            'User',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_ban_id_user', 'Ban');
        $this->dropForeignKey('fk_ban_issued_by', 'Ban');
        $this->dropTable('Ban');
    }
}
