<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;
use app\models\Rating;
use yii\data\ActiveDataProvider;

/**
 * This is the ActiveQuery class for [[Training]].
 *
 * @see Training
 */
class TrainingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Training[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Training|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

/**
 * This is the model class for table "training".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property double $points
 *
 * @property User $user
 */
class Training extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'training';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            [['user_id'], 'integer'],
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
            'name' => 'Назва наукової роботи',
			'points' => 'Кількість балів',
        ];
    }

	public function addTraining()
	{
		if ($this->validate()) {
			$training = new Training();
			$training->user_id = Yii::$app->user->id;
			$training->name = $this->name;
			$training->points = 3;
			$training->save();
			$rating =($rating = Rating::findOne(['user_id' => Yii::$app->user->id])) ? $rating :new Rating();
			$summ = Training::find()
				->where(['user_id' => Yii::$app->user->id])
				->sum('points');
			$rating->ptraining = $summ;
			$rating->save();
			$rating->prating = Rating::updatePRating(Yii::$app->user->id);
			$rating->save();
		}
	}

	public static function setSumAfterDel()
	{
		$rating = Rating::findOne(['user_id' => Yii::$app->user->id]);
		$summ = Training::find()
			->where(['user_id' => Yii::$app->user->id])
			->sum('points');
		$rating->ptraining = $summ;
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
        return new TrainingQuery(get_called_class());
    }

    public function search($params)
    {
        $query = Training::find();

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
