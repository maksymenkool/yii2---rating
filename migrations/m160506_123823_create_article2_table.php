<?php

use yii\db\Migration;

/**
 * Handles the creation for table `article2`.
 */
class m160506_123823_create_article2_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article2', [
            'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull(),
			'item' => $this->integer(2)->notNull(),
			'item_text' => $this->string(200)->notNull(),
			'name' => $this->text()->notNull(),
			'pages_count' => $this->integer(4)->notNull(),
			'auth_count' => $this->integer(2)->notNull(),
			'points' => $this->double(10,2)->notNull(),
        ]);
		
		$this->addForeignKey('article2_user_id', 'article2', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
		$this->createIndex('idx_article2_points', 'article2', 'points');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
		$this->dropForeignKey('article2_user_id', 'article2');
        $this->dropTable('article2');
    }
}
