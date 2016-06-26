<?php

use yii\db\Migration;

/**
 * Handles the creation for table `research`.
 */
class m160506_172053_create_research_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('research', [
            'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull(),
			'quality' => $this->integer(2)->notNull(),
			'quality_text' => $this->string(150)->notNull(),
			'name' => $this->text()->notNull(),
			'place' => $this->integer(2)->notNull(),
			'points' => $this->double(10,2)->notNull(),
        ]);
		
		$this->addForeignKey('research_user_id', 'research', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
		$this->createIndex('idx_research_points', 'research', 'points');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('research_user_id', 'research');
        $this->dropTable('research');
    }
}
