<?php

use yii\db\Migration;

/**
 * Class m241218_174824_test_room
 */
class m241218_174824_test_room extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if (strtolower(YII_ENV) === 'dev') {
            $this->insert('Room', [
                'id' => 1,
                'game_type' => \app\modules\game\models\base\BaseGameType::TYPE_LUDO,
                'game_history' => '',
                'current_gamestate' => '',
            ]);

            $this->insert('User_Room', [
                'id' => 1,
                'id_user' => 1,
                'id_room' => '1',
                'player_number' => '0',
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if (strtolower(YII_ENV) === 'dev') {
            $this->delete('Room', [
                'id' => [1],
            ]);
            $this->delete('User_Room', [
                'id' => [1],
            ]);
        }
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241218_174824_test_room cannot be reverted.\n";

        return false;
    }
    */
}
