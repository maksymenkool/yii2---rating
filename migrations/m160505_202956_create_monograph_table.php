<?php

use yii\db\Migration;

/**
 * Handles the creation for table `monograph`.
 */
class m160505_202956_create_monograph_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('monograph', [
            'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull(),
			'name' => $this->text()->notNull(),
			'pages_count' => $this->integer(4)->notNull(),
			'auth_count' => $this->integer(2)->notNull(),
			'lang' => $this->integer(2)->notNull(),
			'science' => $this->integer(2)->notNull(),
			'points' => $this->double(10,2)->notNull(),
			
        ]);
		
		$this->addForeignKey('monograph_user_id', 'monograph', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
		$this->createIndex('idx_monograph_points', 'monograph', 'points');
        
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('monograph_user_id', 'monograph');
		$this->dropTable('monograph');
    }
}
