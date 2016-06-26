<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;
use app\models\Rating;
use yii\data\ActiveDataProvider;

/**
 * This is the ActiveQuery class for [[Article2]].
 *
 * @see Article2
 */
class Article2Query extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Article2[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Article2|array|null
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
 * @property integer $item
 * @property string $item_text
 * @property string $name
 * @property integer $pages_count
 * @property integer $auth_count
 * @property double $points
 *
 * @property User $user
 */
class Article2 extends \yii\db\ActiveRecord
{
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article2';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item', 'name', 'pages_count', 'auth_count'], 'required'],
            [['user_id', 'pages_count', 'auth_count'], 'integer'],
            [['item_text', 'name'], 'string'],
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
			'item' => 'Оберіть необхідне',
			'item_text' => 'Вид статті',
            'name' => 'Назва публікації',
			'pages_count' => 'Кількість сторінок',
            'auth_count' => 'Кількість авторів',
            'points' => 'Кількість балів',
        ];
    }

	public function addArticle2()
	{
		if ($this->validate()) {
			$article = new Article2();
			$article->user_id = Yii::$app->user->id;
			$article->item = $this->item;
			$article->setItemText($article->item);
			$article->name = $this->name;
			$article->auth_count = $this->auth_count;
			$article->pages_count = $this->pages_count;
			$article->setPoints($article->item, $article->auth_count, $article->pages_count);
			$article->save();
			$rating = ($rating = Rating::findOne(['user_id' => Yii::$app->user->id])) ? $rating :new Rating();
			$summ = Article2::find()
				->where(['user_id' => Yii::$app->user->id])
				->sum('points');
			$rating->particle2 = $summ;
			$rating->save();			
			$rating->prating = Rating::updatePRating(Yii::$app->user->id);
			$rating->save();
		}
	}

	public function setItemText($item)
	{
		if($item == 2){
			$this->item_text = 'Статті в журналах, що входять до міжнародних науковометричних баз даних (SCOPUS, Webometrics  та ISI Master Journal List)';
		} elseif($item == 3){
			$this->item_text = 'Статті в журналах, що написані англійською або німецькою мовами та надруковані закордоном';
		} elseif($item == 4){
			$this->item_text = 'Статті в журналах, що вийшли за кордоном але написані українською або рос. мовами  або статті англійською мовою, що надруковані в Україні';
		} elseif($item == 5){
			$this->item_text = 'Статті у фахових виданнях України';
		}
	}

	public function setPoints($item, $auth, $page)
	{
		if($item == 2){
			$this->points = $page * 15 / $auth;
		} elseif($item == 3){
			$this->points = $page * 8 / $auth;
		} elseif($item == 4){
			$this->points = $page * 6 / $auth;
		} elseif($item == 5){
			$this->points = $page * 4 / $auth;
		}
	}

	public static function setSumAfterDel()
	{
		$rating = Rating::findOne(['user_id' => Yii::$app->user->id]);
		$summ = Article2::find()
			->where(['user_id' => Yii::$app->user->id])
			->sum('points');
		$rating->particle2 = $summ;
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
        return new Article2Query(get_called_class());
    }	

    public function search($params)
    {
        $query = Article2::find();

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
            'user_id' => Yii::$app->user->id
        ]);

        return $dataProvider;
    }
}
