<?php

use yii\db\Migration;

/**
 * Class m250108_202718_add_seed_to_room
 */
class m250108_202718_add_seed_to_room extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('Room', 'seed', $this->integer()->after('current_gamestate')->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('Room', 'seed');
    }
}
