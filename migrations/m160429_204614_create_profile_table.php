<?php

use yii\db\Migration;

/**
 * Handles the creation for table `profile_table`.
 */
class m160429_204614_create_profile_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
		$this->createTable('profile', [
            'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull(),
			'first_name' => $this->string(50),
            'second_name' => $this->string(50),
            'middle_name' => $this->string(50),
			'age' => $this->integer(4),
			'department' => $this->string(100),
			'position' => $this->smallInteger(),
			'position_text' => $this->string(20),
        ]);
		
		$this->addForeignKey('profile_user_id', 'profile', 'user_id', 'user', 'id');
		
		$this->createIndex('idx_profile_first_name', 'profile', 'first_name');
        $this->createIndex('idx_profile_department', 'profile', 'department');
        $this->createIndex('idx_profile_position', 'profile', 'position');
		
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
		$this->dropForeignKey('profile_user_id', 'profile');
		$this->dropTable('profile');
    }
}
