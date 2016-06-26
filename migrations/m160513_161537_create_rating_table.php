<?php

use yii\db\Migration;

/**
 * Handles the creation for table `rating`.
 */
class m160513_161537_create_rating_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('rating', [
            'id' => $this->primaryKey(),
			'user_id' => $this->integer()->notNull(),
			'name' => $this->string()->notNull(),
			'pmonograph' => $this->double(10,2)->defaultValue(0),
			'particle2' => $this->double(10,2)->defaultValue(0),
			'particle6' => $this->double(10,2)->defaultValue(0),
			'ppatent' => $this->double(10,2)->defaultValue(0),
			'preports' => $this->double(10,2)->defaultValue(0),
			'presearch' => $this->double(10,2)->defaultValue(0),
			'ptraining' => $this->double(10,2)->defaultValue(0),
			'pstudwork' => $this->double(10,2)->defaultValue(0),
			'pvictory' => $this->double(10,2)->defaultValue(0),
			'pother' => $this->double(10,2)->defaultValue(0),
			'prating' => $this->double(10,2)->defaultValue(0),
        ]);
		
		$this->addForeignKey('rating_user_id', 'rating', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
		$this->createIndex('idx_rating_pmonograph', 'rating', 'pmonograph');
		$this->createIndex('idx_rating_particle2', 'rating', 'particle2');
		$this->createIndex('idx_rating_particle6', 'rating', 'particle6');
		$this->createIndex('idx_rating_ppatent', 'rating', 'ppatent');
		$this->createIndex('idx_rating_preports', 'rating', 'preports');
		$this->createIndex('idx_rating_presearch', 'rating', 'presearch');
		$this->createIndex('idx_rating_ptraining', 'rating', 'ptraining');
		$this->createIndex('idx_rating_pstudwork', 'rating', 'pstudwork');
		$this->createIndex('idx_rating_pvictory', 'rating', 'pvictory');
		$this->createIndex('idx_rating_pother', 'rating', 'pother');
		$this->createIndex('idx_rating_prating', 'rating', 'prating');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('rating_user_id', 'rating');
        $this->dropTable('rating');
    }
}
