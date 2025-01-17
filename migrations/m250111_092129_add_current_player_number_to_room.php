<?php

use yii\db\Migration;

/**
 * Class m250111_092129_add_current_player_number_to_room
 */
class m250111_092129_add_current_player_number_to_room extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('Room', 'current_player_number', $this->integer()->after('id_chat')->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('Room', 'current_player_number');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250111_092129_add_current_player_number_to_room cannot be reverted.\n";

        return false;
    }
    */
}
