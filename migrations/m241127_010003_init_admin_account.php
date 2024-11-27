<?php

use yii\db\Migration;

/**
 * Class m241127_010003_init_admin_account
 */
class m241127_010003_init_admin_account extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('User', [
            'id' => 1,
            'id_role' => 30,
            'username' => 'root',
            'email' => 'gug.kamil@gmail.com',
            'password' => Yii::$app->getSecurity()->generatePasswordHash('root'),
            'visible_name' => 'Main Admin',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('User', [
            'id' => 1,
        ]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241127_010003_init_admin_account cannot be reverted.\n";

        return false;
    }
    */
}
