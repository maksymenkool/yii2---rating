<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;
use app\models\Rating;
use yii\data\ActiveDataProvider;

/**
 * This is the ActiveQuery class for [[Victory]].
 *
 * @see Victory
 */
class VictoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Victory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Victory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

/**
 * This is the model class for table "victory".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $quality
 * @property integer $place
 * @property string $name
 * @property double $points
 *
 * @property User $user
 */
class Victory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'victory';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'place'], 'required'],
            [['user_id', 'place'], 'integer'],
            [['name'], 'string'],
            [['points'], 'number'],
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
			'place' => 'Місце проведення',
            'name' => 'Назва диплому / грамоти ',
			'points' => 'Кількість балів',
        ];
    }

	public function addVictory()
	{
		if ($this->validate()) {
			$victory = new Victory();
			$victory->user_id = Yii::$app->user->id;
			$victory->name = $this->name;
			$victory->place = $this->place;
			$victory->setPoints($victory->place);
			$victory->save();
			$rating =($rating = Rating::findOne(['user_id' => Yii::$app->user->id])) ? $rating :new Rating();
			$summ = Victory::find()
				->where(['user_id' => Yii::$app->user->id])
				->sum('points');
			$rating->pvictory = $summ;
			$rating->save();
			$rating->prating = Rating::updatePRating(Yii::$app->user->id);
			$rating->save();
		}
	}
	
	public function setPoints($place)
	{
		if($place == 1){
			$this->points = 3 * $place;
		} elseif($place == 2){
			$this->points = 3 * $place;
		}
	}

	public static function setSumAfterDel()
	{
		$rating = Rating::findOne(['user_id' => Yii::$app->user->id]);
		$summ = Victory::find()
			->where(['user_id' => Yii::$app->user->id])
			->sum('points');
		$rating->pvictory = $summ;
		$rating->save();
		$rating->prating = Rating::updatePRating(Yii::$app->user->id);
		$rating->save();
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
        return new VictoryQuery(get_called_class());
    }	

    public function search($params)
    {
        $query = Victory::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where([
				'user_id' => Yii::$app->user->id
			]);
            return $dataProvider;
        }

        $query->andFilterWhere([
            'user_id' => Yii::$app->user->id,
        ]);

        return $dataProvider;
    }
}
