<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;
use yii\data\ActiveDataProvider;

/**
 * This is the ActiveQuery class for [[Rating]].
 *
 * @see Rating
 */
class RatingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Rating[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Rating|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

/**
 * This is the model class for table "rating".
 *
 * @property integer $id
 * @property integer $user_id
 * @property double $pmonograph
 * @property double $particle2
 * @property double $particle6
 * @property double $ppatent
 * @property double $preports
 * @property double $presearch
 * @property double $ptraining
 * @property double $pstudwork
 * @property double $pvictory
 * @property double $pother
 * @property double $prating
 *
 * @property User $user
 */
class Rating extends \yii\db\ActiveRecord
{
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rating';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
			[['name'], 'string'],
			[['pmonograph', 'particle2', 'particle6', 'ppatent', 'preports', 'presearch', 'ptraining', 'pstudwork', 'pvictory', 'pother', 'prating'], 'number'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
			'name' => 'Фамілія ім\'я по батькові',
            'prating' => 'Кількість баллів',
        ];
    }

	public static function updatePRating($userId)
	{
		return Rating::find()
			->where(['user_id' => $userId])
			->sum('pmonograph+particle2+particle6+ppatent+preports+presearch+ptraining+pstudwork+pvictory+pother');
		
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function find()
    {
        return new RatingQuery(get_called_class());
    }	

    public function search($params)
    {
        $query = Rating::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->orderBy([
				'prating' => SORT_DESC,
			]);
            return $dataProvider;
        }

		$query->orderBy([
				'prating' => SORT_DESC,
			]);
        
        return $dataProvider;
    }
}
