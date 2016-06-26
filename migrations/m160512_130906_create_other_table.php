<?php

use yii\db\Migration;

/**
 * Handles the creation for table `other`.
 */
class m160512_130906_create_other_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('other', [
            'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull(),
			'item' => $this->integer()->notNull(),
			'item_text' => $this->string(200)->notNull(),
			'name' => $this->text()->notNull(),
			'place' => $this->integer(2),
			'points' => $this->double(10,2)->notNull(),
        ]);
		
		$this->addForeignKey('other_user_id', 'other', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
		$this->createIndex('idx_other_points', 'other', 'points');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('other_user_id', 'other');
        $this->dropTable('other');
    }
}
