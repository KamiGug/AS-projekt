<?php

use yii\db\Migration;

/**
 * Class m241126_232828_init
 */
class m241126_232828_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //User table
        $this->createTable('User', [
            'id' => $this->primaryKey(),
            'id_role' => $this->integer(),
            'username' => $this->string(40)->notNull()->unique(),
            'email' => $this->string(40),
            'password' => $this->string(150)->notNull(),
            'visible_name' => $this->string(30)->notNull(),
            'modified_at' => $this->dateTime()->defaultExpression(new \yii\db\Expression('CURRENT_TIMESTAMP')),
            'modified_by' => $this->integer(),
            'created_at' => $this->dateTime()->defaultExpression(new \yii\db\Expression('CURRENT_TIMESTAMP')),
            'created_by' => $this->integer(),
        ]);
        $this->addForeignKey(
            'fk_user_modified_by',
            'User',
            'modified_by',
            'User',
            'id',
            'SET NULL',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_user_created_by',
            'User',
            'created_by',
            'User',
            'id',
            'SET NULL',
            'CASCADE'
        );
        // add a trigger for updating modified_at
        $this->execute("
            CREATE TRIGGER trigger_update_user_modified_at
            BEFORE UPDATE ON User
            FOR EACH ROW
            BEGIN
                SET NEW.modified_at = CURRENT_TIMESTAMP;
            END;
        ");

        //Role table
        $this->createTable('Role', [
            'id' => $this->primaryKey(),
            'name' => $this->string(20)->notNull(),
            'deactivated_at' => $this->dateTime()->defaultValue(null),
            'created_at' => $this->dateTime()->defaultExpression(new \yii\db\Expression('CURRENT_TIMESTAMP')),
            'modified_at' => $this->dateTime()->defaultExpression(new \yii\db\Expression('CURRENT_TIMESTAMP')),
            'modified_by' => $this->integer(),
        ]);
        $this->addForeignKey(
            'fk_role_modified_by',
            'Role',
            'modified_by',
            'User',
            'id',
            'SET NULL',
            'CASCADE'
        );
        // add a trigger for updating modified_at
        $this->execute("
            CREATE TRIGGER trigger_update_role_modified_at
            BEFORE UPDATE ON Role
            FOR EACH ROW
            BEGIN
                SET NEW.modified_at = CURRENT_TIMESTAMP;
            END;
        ");

        $this->addForeignKey(
            'fk_user_id_role',
            'User',
            'id_role',
            'Role',
            'id',
            'SET NULL',
            'CASCADE'
        );

        //Chat table
        $this->createTable('Chat', [
            'id' => $this->primaryKey(),
            'created_at' => $this->dateTime()->defaultExpression(new \yii\db\Expression('CURRENT_TIMESTAMP')),
            'created_by' => $this->integer(),
        ]);
        $this->addForeignKey(
            'fk_chat_created_by',
            'Chat',
            'created_by',
            'User',
            'id',
            'SET NULL',
            'CASCADE'
        );

        //ChatParticipant table
        $this->createTable('ChatParticipant', [
            'id' => $this->primaryKey(),
            'id_user' => $this->integer()->notNull(),
            'id_chat' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->defaultExpression(new \yii\db\Expression('CURRENT_TIMESTAMP')),
        ]);
        $this->addForeignKey(
            'fk_chat_participant_id_user',
            'ChatParticipant',
            'id_user',
            'User',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_chat_participant_id_chat',
            'ChatParticipant',
            'id_chat',
            'User',
            'id',
            'CASCADE',
            'CASCADE'
        );
        
        //Message table
        $this->createTable('Message', [
            'id' => $this->primaryKey(),
            'id_chat' => $this->integer(),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime()->defaultExpression(new \yii\db\Expression('CURRENT_TIMESTAMP')),
            'contents' => $this->text()->notNull(),
        ]);
        $this->addForeignKey(
            'fk_message_created_by',
            'Message',
            'created_by',
            'User',
            'id',
            'SET NULL',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_message_id_chat',
            'Message',
            'id_chat',
            'Chat',
            'id',
            'CASCADE',
            'CASCADE'
        );
        
        //FlaggedMessage table
        $this->createTable('FlaggedMessage', [
            'id' => $this->primaryKey(),
            'id_message' => $this->integer(),
            'created_at' => $this->dateTime()->defaultExpression(new \yii\db\Expression('CURRENT_TIMESTAMP')),
        ]);
        $this->addForeignKey(
            'fk_flagged_message_id_message',
            'FlaggedMessage',
            'id_message',
            'Message',
            'id',
            'CASCADE',
            'CASCADE'
        );


        //User_FlaggedMessage table
        $this->createTable('User_FlaggedMessage', [
            'id' => $this->primaryKey(),
            'id_flagged' => $this->integer()->notNull(),
            'id_user' => $this->integer(),
            'comment' => $this->text()->notNull(),
            'created_at' => $this->dateTime()->defaultExpression(new \yii\db\Expression('CURRENT_TIMESTAMP')),
        ]);
        $this->addForeignKey(
            'fk_user_flagged_message_id_flagged',
            'User_FlaggedMessage',
            'id_flagged',
            'FlaggedMessage',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_user_flagged_message_id_user',
            'User_FlaggedMessage',
            'id_user',
            'User',
            'id',
            'SET NULL',
            'CASCADE'
        );

        //GameType table
        $this->createTable('GameType', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull(),
            'max_players' =>$this->tinyInteger()->unsigned()->notNull(),

        ]);

        //Room table
        $this->createTable('Room', [
            'id' => $this->primaryKey(),
            'id_game_type' => $this->integer()->notNull(),
            'id_chat' => $this->integer(),
            'game_history' => $this->text()->notNull(),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime()->defaultExpression(new \yii\db\Expression('CURRENT_TIMESTAMP')),
        ]);
        $this->addForeignKey(
            'fk_room_id_game_type',
            'Room',
            'id_game_type',
            'GameType',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_room_id_chat',
            'Room',
            'id_chat',
            'Chat',
            'id',
            'SET NULL',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_room_created_by',
            'Room',
            'created_by',
            'User',
            'id',
            'SET NULL',
            'CASCADE'
        );
        
        //User_Room table
        $this->createTable('User_Room', [
            'id' => $this->primaryKey(),
            'id_user' => $this->integer()->notNull(),
            'id_room' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->defaultExpression(new \yii\db\Expression('CURRENT_TIMESTAMP')),
        ]);
        $this->addForeignKey(
            'fk_role_id_user',
            'User_Room',
            'id_user',
            'User',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk_role_id_room',
            'User_Room',
            'id_room',
            'Room',
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
        //drop User_Room
        $this->dropForeignKey('fk_role_id_user', 'User_Room');
        $this->dropForeignKey('fk_role_id_room', 'User_Room');
        $this->dropTable('User_Room');
        //drop Room
        $this->dropForeignKey('fk_room_id_game_type', 'Room');
        $this->dropForeignKey('fk_room_id_chat', 'Room');
        $this->dropForeignKey('fk_room_created_by', 'Room');
        $this->dropTable('Room');
        //drop GameType
        $this->dropTable('GameType');
        //drop User_FlaggedMessage
        $this->dropForeignKey('fk_user_flagged_message_id_flagged', 'User_FlaggedMessage');
        $this->dropForeignKey('fk_user_flagged_message_id_user', 'User_FlaggedMessage');
        $this->dropTable('User_FlaggedMessage');
        //drop FlaggedMessage
        $this->dropForeignKey('fk_flagged_message_id_message', 'FlaggedMessage');
        $this->dropTable('FlaggedMessage');
        //drop Message
        $this->dropForeignKey('fk_message_id_chat', 'Message');
        $this->dropForeignKey('fk_message_created_by', 'Message');
        $this->dropTable('Message');
        //drop ChatParticipant
        $this->dropForeignKey('fk_chat_participant_id_user', 'ChatParticipant');
        $this->dropForeignKey('fk_chat_participant_id_chat', 'ChatParticipant');
        $this->dropTable('ChatParticipant');        
        //drop Chat
        $this->dropForeignKey('fk_chat_created_by', 'Chat');
        $this->dropTable('Chat');
        //drop Role
        $this->execute("
            DROP TRIGGER IF EXISTS trigger_update_role_modified_at;
        ");
        $this->dropForeignKey('fk_user_id_role', 'User');
        $this->dropForeignKey('fk_role_modified_by', 'Role');
        $this->dropTable('Role');
        $this->execute("
            DROP TRIGGER IF EXISTS trigger_update_user_modified_at;
        ");
        //drop User
        $this->dropForeignKey('fk_user_modified_by', 'User');
        $this->dropForeignKey('fk_user_created_by', 'User');
        $this->dropTable('User');
    }
}