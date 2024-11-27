<?php

use yii\db\Migration;

/**
 * Class m241127_004330_init_roles
 */
class m241127_004330_init_roles extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('Role', [
            'id' => 10,
            'name' => 'Player',
        ]);
        $this->insert('Role', [
            'id' => 20,
            'name' => 'Moderator',
        ]);
        $this->insert('Role', [
            'id' => 30,
            'name' => 'Admin',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('Role', [
            'id' => [10, 20, 30],
        ]);
    }
}
