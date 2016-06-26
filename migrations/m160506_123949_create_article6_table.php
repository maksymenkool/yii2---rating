<?php

use yii\db\Migration;

/**
 * Handles the creation for table `article6`.
 */
class m160506_123949_create_article6_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article6', [
            'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull(),
			'name' => $this->text()->notNull(),
			'pages_count' => $this->integer()->notNull(6),
			'auth_count' => $this->integer()->notNull(2),
			'place' => $this->integer()->notNull(2),
			'points' => $this->double()->notNull(10,2),
        ]);
		
		$this->addForeignKey('article6_user_id', 'article6', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
		$this->createIndex('idx_article6_points', 'article6', 'points');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('article6_user_id', 'article6');
        $this->dropTable('article6');
    }
}
