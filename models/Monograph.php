<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;
use app\models\Rating;
use yii\data\ActiveDataProvider;

/**
 * This is the ActiveQuery class for [[Other]].
 *
 * @see Other
 */
class MonographQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Monograph[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Monograph|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

/**
 * This is the model class for table "monograph".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property integer $pages_count
 * @property integer $auth_count
 * @property integer $lang
 * @property integer $science
 * @property double $points
 *
 * @property User $user
 */
class Monograph extends \yii\db\ActiveRecord
{
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'monograph';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'pages_count', 'auth_count', 'lang', 'science'], 'required'],
            [['user_id', 'pages_count', 'auth_count', 'lang', 'science'], 'integer'],
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
            'name' => 'Назва публікації',
			'pages_count' => 'Кількість сторінок',
            'auth_count' => 'Кількість авторів',
            'lang' => 'Мова видання',
            'science' => 'Галузь Наук',
            'points' => 'Кількість балів',
        ];
    }

	public function addMonograph()
	{
		if ($this->validate()) {
			$monograph = new Monograph();
			$monograph->user_id = Yii::$app->user->id;
			$monograph->name = $this->name;
			$monograph->pages_count = $this->pages_count;			
			$monograph->auth_count = $this->auth_count;
			$monograph->lang = $this->lang;
			$monograph->science = $this->science;
			$monograph->setPoints($monograph->pages_count, $monograph->auth_count, $monograph->lang, $monograph->science);
			$monograph->save();
			$rating =($rating = Rating::findOne(['user_id' => Yii::$app->user->id])) ? $rating :new Rating();
			$summ = Monograph::find()
				->where(['user_id' => Yii::$app->user->id])
				->sum('points');
			$rating->pmonograph = $summ;
			$rating->save();
			$rating->prating = Rating::updatePRating(Yii::$app->user->id);
			$rating->save();
		}
	}

	public function setPoints($page, $auth, $lang, $science)
	{
		if ($lang == 1){
			$k = 1;
		} elseif ($lang == 2){
			$k = 2;
		}

		if ($science == 1){
			$m = 1;
		} elseif ($science == 2){
			$m = 0.7;
		}
		$this->points = $k * $m * ($page / (5 * $auth));
	}

	public static function setSumAfterDel()
	{
		$rating = Rating::findOne(['user_id' => Yii::$app->user->id]);
		$summ = Monograph::find()
			->where(['user_id' => Yii::$app->user->id])
			->sum('points');
		$rating->pmonograph = $summ;
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
        return new MonographQuery(get_called_class());
    }
	
	
    public function search($params)
    {
        $query = Monograph::find();

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
