<?php

use yii\db\Migration;

/**
 * Handles the creation for table `training`.
 */
class m160512_123336_create_training_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('training', [
            'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull(),
			'name' => $this->text()->notNull(),
			'points' => $this->double(10,2)->notNull(),
        ]);
		
		$this->addForeignKey('training_user_id', 'training', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
		$this->createIndex('idx_training_points', 'training', 'points');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('training_user_id', 'training');
        $this->dropTable('training');
    }
}
