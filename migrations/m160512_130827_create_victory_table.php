<?php

use yii\db\Migration;

/**
 * Handles the creation for table `victory`.
 */
class m160512_130827_create_victory_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('victory', [
            'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull(),
			'name' => $this->text()->notNull(),
			'place' => $this->integer(2)->notNull(),
			'points' => $this->double(10,2)->notNull(),
        ]);
		
		$this->addForeignKey('victory_user_id', 'victory', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
		$this->createIndex('idx_victory_points', 'victory', 'points');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('victory_user_id', 'victory');
        $this->dropTable('victory');
    }
}
