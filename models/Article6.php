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
class Article6Query extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Article6[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Article6|array|null
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
 * @property string $name
 * @property integer $pages_count
 * @property integer $auth_count
 * @property integer $place
 * @property double $points
 *
 * @property User $user
 */
class Article6 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article6';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'pages_count', 'auth_count', 'place'], 'required'],
            [['user_id', 'pages_count', 'auth_count', 'place'], 'integer'],
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
			'place' => 'Місце видання',
            'points' => 'Кількість балів',
        ];
    }

	public function addArticle6()
	{
		if ($this->validate()) {
			$article = new Article6();
			$article->user_id = Yii::$app->user->id;
			$article->name = $this->name;
			$article->auth_count = $this->auth_count;
			$article->pages_count = $this->pages_count;
			$article->place = $this->place;
			$article->setPoints($article->auth_count, $article->pages_count, $article->place);
			$article->save();
			$rating = ($rating = Rating::findOne(['user_id' => Yii::$app->user->id])) ? $rating :new Rating();
			$summ = Article6::find()
				->where(['user_id' => Yii::$app->user->id])
				->sum('points');
			$rating->particle6 = $summ;
			$rating->save();			
			$rating->prating = Rating::updatePRating(Yii::$app->user->id);
			$rating->save();
		}
	}
	
	public function setPoints($auth, $page, $place)
	{
		$this->points = $page * $place * 2 / $auth;
	}

	public static function setSumAfterDel()
	{
		$rating = Rating::findOne(['user_id' => Yii::$app->user->id]);
		$summ = Article6::find()
			->where(['user_id' => Yii::$app->user->id])
			->sum('points');
		$rating->particle6 = $summ;
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
        return new Article6Query(get_called_class());
    }	

    public function search($params)
    {
        $query = Article6::find();

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
