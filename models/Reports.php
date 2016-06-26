<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;
use app\models\Rating;
use yii\data\ActiveDataProvider;

/**
 * This is the ActiveQuery class for [[Patent]].
 *
 * @see Other
 */
class ReportsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Reports[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Reports|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

/**
 * This is the model class for table "article2".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $kind_report
 * @property string $name
 * @property double $points
 *
 * @property User $user
 */
class Reports extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reports';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['kind_report', 'name'], 'required'],
            [['user_id', 'kind_report'], 'integer'],
            [['kind_text', 'name'], 'string'],
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
			'kind_report' => 'Місце виступу',
			'kind_text' => 'Місце виступу',
            'name' => 'Назва доповіді',
			'points' => 'Кількість балів',
        ];
    }

	public function addReports()
	{
		if ($this->validate()) {
			$report = new Reports();
			$report->user_id = Yii::$app->user->id;
			$report->kind_report = $this->kind_report;
			$report->setKindText($report->kind_report);
			$report->name = $this->name;
			$report->setPoints($report->kind_report);
			$report->save();
			$rating =($rating = Rating::findOne(['user_id' => Yii::$app->user->id])) ? $rating :new Rating();
			$summ = Reports::find()
				->where(['user_id' => Yii::$app->user->id])
				->sum('points');
			$rating->preports = $summ;
			$rating->save();
			$rating->prating = Rating::updatePRating(Yii::$app->user->id);
			$rating->save();
		}
	}

	public function setKindText($kind)
	{
		if($kind == 1){
			$this->kind_text = 'Виступи на всеукраїнських конференціях';
		} elseif($kind == 2){
			$this->kind_text = 'Виступи на міжнародних конференціях';
		}
	}

	public function setPoints($kind)
	{
		if($kind == 1){
			$this->points = 0.2;
		} elseif($kind == 2){
			$this->points = 0.3;
		}
	}

	public static function setSumAfterDel()
	{
		$rating = Rating::findOne(['user_id' => Yii::$app->user->id]);
		$summ = Reports::find()
			->where(['user_id' => Yii::$app->user->id])
			->sum('points');
		$rating->preports = $summ;
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
        return new ReportsQuery(get_called_class());
    }	
	
    public function search($params)
    {
        $query = Reports::find();

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
