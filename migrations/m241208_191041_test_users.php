<?php

use app\modules\user\models\Authentication\Role;
use yii\db\Migration;

/**
 * Class m241208_191041_test_users
 */
class m241208_191041_test_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if (strtolower(YII_ENV) === 'dev') {
            $this->insert('User', [
                'id' => 2,
                'role' => Role::ROLE_PLAYER,
                'username' => 'test1',
                'email' => 'test1@tester.tested',
                'password' => Yii::$app->getSecurity()->generatePasswordHash('test1'),
                'visible_name' => 'Tester Uno',
            ]);
            $this->insert('User', [
                'id' => 3,
                'role' => Role::ROLE_PLAYER,
                'username' => 'test2',
                'email' => 'test2@tester.tested',
                'password' => Yii::$app->getSecurity()->generatePasswordHash('test2'),
                'visible_name' => 'Tester Dos',
            ]);


            $this->insert('User', [
                'id' => 4,
                'role' => Role::ROLE_TEMPORARY_PLAYER,
                'username' => null,
                'email' => null,
                'password' => null,
                'visible_name' => 'Temporary Tester Uno',
            ]);
            $this->insert('User', [
                'id' => 5,
                'role' => Role::ROLE_TEMPORARY_PLAYER,
                'username' => null,
                'email' => null,
                'password' => null,
                'visible_name' => 'Temporary Tester Dos',
            ]);



        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if (strtolower(YII_ENV) === 'dev') {
            $this->delete('User', [
                'id' => [2, 3, 4, 5],
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
        echo "m241208_191041_test_users cannot be reverted.\n";

        return false;
    }
    */
}
