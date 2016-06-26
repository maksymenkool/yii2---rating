<?php

use yii\db\Migration;

/**
 * Handles the creation for table `user_table`.
 */
class m160429_204543_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
		$this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'email' => $this->string()->notNull(),
            'auth_key' => $this->string(32),
            'password' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
		]);
		
		$this->createIndex('idx_user_username', 'user', 'username');
        $this->createIndex('idx_user_email', 'user', 'email');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('user');
    }
}
