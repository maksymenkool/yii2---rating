<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;
use app\models\Rating;
use yii\data\ActiveDataProvider;

/**
 * This is the ActiveQuery class for [[Research]].
 *
 * @see Research
 */
class ResearchQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Research[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Research|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

/**
 * This is the model class for table "research".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $quality
 * @property string $quality_text
 * @property integer $place
 * @property string $name
 * @property double $points
 *
 * @property User $user
 */
class Research extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'research';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['quality', 'name', 'place'], 'required'],
            [['user_id', 'quality', 'place'], 'integer'],
            [['quality_text', 'name'], 'string'],
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
			'place' => 'Місце дослідження',
			'quality' => 'Якість участі',
			'quality_text' => 'Якість участі',
            'name' => 'Назва НДР',
			'points' => 'Кількість балів',
        ];
    }

	public function addResearch()
	{
		if ($this->validate()) {
			$research = new Research();
			$research->user_id = Yii::$app->user->id;
			$research->quality = $this->quality;
			$research->setQualityText($research->quality);
			$research->name = $this->name;
			$research->place = $this->place;
			$research->setPoints($research->quality, $research->place);
			$research->save();
			$rating =($rating = Rating::findOne(['user_id' => Yii::$app->user->id])) ? $rating :new Rating();
			$summ = Research::find()
				->where(['user_id' => Yii::$app->user->id])
				->sum('points');
			$rating->presearch = $summ;
			$rating->save();
			$rating->prating = Rating::updatePRating(Yii::$app->user->id);
			$rating->save();
		}
	}
	
	public function setQualityText($quality)
	{
		if($quality == 1){
			$this->quality_text = 'Керівник';
		} elseif($quality == 2){
			$this->quality_text = 'Відповідальний виконавець';
		} elseif($quality == 3){
			$this->quality_text = 'Виконавець';
		}
	}	
	
	public function setPoints($quality, $place)
	{
		if($quality == 1){
			$this->points = 10 * $place;
		} elseif($quality == 2){
			$this->points = 5 * $place;
		} elseif($quality == 3){
			$this->points = 1 * $place;
		}
	}

	public static function setSumAfterDel()
	{
		$rating = Rating::findOne(['user_id' => Yii::$app->user->id]);
		$summ = Research::find()
			->where(['user_id' => Yii::$app->user->id])
			->sum('points');
		$rating->presearch = $summ;
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
        return new ResearchQuery(get_called_class());
    }

    public function search($params)
    {
        $query = Research::find();

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
