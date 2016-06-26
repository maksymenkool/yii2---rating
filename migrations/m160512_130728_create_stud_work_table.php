<?php

use yii\db\Migration;

/**
 * Handles the creation for table `stud_work`.
 */
class m160512_130728_create_stud_work_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('stud_work', [
            'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull(),
			'type' => $this->integer(4)->notNull(),
			'type_text' => $this->string(250)->notNull(),
			'name' => $this->text()->notNull(),
			'points' => $this->double(10,2)->notNull(),
        ]);
		
		$this->addForeignKey('stud_work_user_id', 'stud_work', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
		$this->createIndex('idx_stud_work_points', 'stud_work', 'points');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('stud_work_user_id', 'stud_work');
        $this->dropTable('stud_work');
    }
}
