<?php

use yii\db\Migration;

/**
 * Handles the creation for table `reports`.
 */
class m160506_162304_create_reports_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('reports', [
            'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull(),
			'kind_report' => $this->integer(2)->notNull(),
			'kind_text' => $this->string(50)->notNull(),
			'name' => $this->text()->notNull(),
			'points' => $this->double(10,2)->notNull(),
        ]);
		
		$this->addForeignKey('reports_user_id', 'reports', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
		$this->createIndex('idx_reports_points', 'reports', 'points');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('reports_user_id', 'reports');
        $this->dropTable('reports');
    }
}
