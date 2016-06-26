<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;
use app\models\Rating;
use yii\data\ActiveDataProvider;

/**
 * This is the ActiveQuery class for [[Studwork]].
 *
 * @see Studwork
 */
class StudworkQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Studwork[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Studwork|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

/**
 * This is the model class for table "stud_work".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property string $type_text
 * @property string $name
 * @property double $points
 *
 * @property User $user
 */
class Studwork extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'stud_work';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'name'], 'required'],
            [['user_id', 'type'], 'integer'],
            [['type_text', 'name'], 'string'],
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
			'type' => 'Вид робіт',
            'type_text' => 'Вид робіт',
            'name' => 'Назва роботи',
			'points' => 'Бали',
        ];
    }

	public function addStudwork()
	{
		if ($this->validate()) {
			$work = new Studwork();
			$work->user_id = Yii::$app->user->id;
			$work->type = $this->type;
			$work->setTypeText($work->type);
			$work->name = $this->name;
			$work->setPoints($work->type);
			$work->save();
			$rating =($rating = Rating::findOne(['user_id' => Yii::$app->user->id])) ? $rating :new Rating();
			$summ1 = Studwork::find()
				->where(['user_id' => Yii::$app->user->id, 'type' => 1])
				->sum('points');
			$summ2 = Studwork::find()
				->where(['user_id' => Yii::$app->user->id, 'type' => 2])
				->sum('points');
			if($summ1 > 5){
				$summ1 = 5;
			}
			if($summ2 > 8){
				$summ2 = 8;
			}
			$rating->pstudwork = $summ1 + $summ2;
			$rating->save();
			$rating->prating = Rating::updatePRating(Yii::$app->user->id);
			$rating->save();
		}
	}

	public function setTypeText($type)
	{
		if($type == 1){
			$this->type_text = 'Опублікування статей або тез в матеріалах (працях) конференцій під керівництвом учасника конкурсу «Кращий молодий науковець року» (автором є студент)';
		} elseif($type == 2){
			$this->type_text = 'Захисти кваліфікаційних магістерських робіт під керівництвом учасника конкурсу «Кращий молодий науковець року»';
		}
	}

	public function setPoints($type)
	{
		if($type == 1){
			$this->points = 1;
		} elseif($type == 2){
			$this->points = 2;
		}
	}

	public static function setSumAfterDel()
	{
		$rating = Rating::findOne(['user_id' => Yii::$app->user->id]);
		$summ1 = Studwork::find()
			->where(['user_id' => Yii::$app->user->id, 'type' => 1])
			->sum('points');
		$summ2 = Studwork::find()
			->where(['user_id' => Yii::$app->user->id, 'type' => 2])
			->sum('points');
		if($summ1 > 5){
			$summ1 = 5;
		}
		if($summ2 > 8){
			$summ2 = 8;
		}
		$rating->pstudwork = $summ1 + $summ2;
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
        return new StudworkQuery(get_called_class());
    }

    public function search($params)
    {
        $query = Studwork::find();

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
