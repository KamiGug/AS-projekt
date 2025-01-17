<?php

use yii\db\Migration;

/**
 * Class m250117_004257_add_room_name_to_room
 */
class m250117_004257_add_room_name_to_room extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('Room', 'name', $this->string(16)->after('id')->notNull());
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
        echo "m250117_004257_add_room_name_to_room cannot be reverted.\n";

        return false;
    }
    */
}
