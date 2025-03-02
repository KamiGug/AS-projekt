<?php

use yii\db\Migration;

/**
 * Class m250302_122953_add_create
 */
class m250302_122953_add_create extends Migration
{
    public function safeUp()
    {
        $this->execute("
            CREATE TRIGGER trigger_insert_user_modified_at_created_at
            BEFORE INSERT ON User
            FOR EACH ROW
            BEGIN
                SET NEW.created_at = CURRENT_TIMESTAMP;
                SET NEW.modified_at = CURRENT_TIMESTAMP;
            END;
        ");

        $this->execute("
            CREATE TRIGGER trigger_insert_chat_created_at
            BEFORE INSERT ON User
            FOR EACH ROW
            BEGIN
                SET NEW.created_at = CURRENT_TIMESTAMP;
            END;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->execute("
            DROP TRIGGER IF EXISTS trigger_insert_user_modified_at_created_at;
        ");
        $this->execute("
            DROP TRIGGER IF EXISTS trigger_insert_chat_created_at;
        ");
    }
}
