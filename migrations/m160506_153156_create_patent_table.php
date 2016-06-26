<?php

use yii\db\Migration;

/**
 * Handles the creation for table `patent`.
 */
class m160506_153156_create_patent_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('patent', [
            'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull(),
			'item' => $this->integer(2)->notNull(),
			'item_text' => $this->string(200)->notNull(),
			'name' => $this->text()->notNull(),
			'auth_count' => $this->integer(2)->notNull(),
			'place' => $this->integer(2)->notNull(),
			'points' => $this->double(10,2)->notNull(),
        ]);
		
		$this->addForeignKey('patent_user_id', 'patent', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
		$this->createIndex('idx_patent_points', 'patent', 'points');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('patent_user_id', 'patent');
        $this->dropTable('patent');
    }
}
