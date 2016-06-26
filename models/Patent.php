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
 * @see Patent
 */
class PatentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Patent[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Patent|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

/**
 * This is the model class for table "patent".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $item
 * @property string $item_text
 * @property string $name
 * @property integer $auth_count
 * @property integer $place
 * @property double $points
 *
 * @property User $user
 */
class Patent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'patent';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item', 'name', 'auth_count', 'place'], 'required'],
            [['user_id', 'auth_count', 'place'], 'integer'],
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
			'item_text' => 'Патент',
            'name' => 'Назва патенту',
			'auth_count' => 'Кількість авторів',
			'place' => 'Місце видання',
            'points' => 'Бали',
        ];
    }

	public function addPatent()
	{
		if ($this->validate()) {
			$patent = new Patent();
			$patent->user_id = Yii::$app->user->id;
			$patent->item = $this->item;
			$patent->setItemText($patent->item);
			$patent->name = $this->name;
			$patent->auth_count = $this->auth_count;
			$patent->place = $this->place;
			$patent->setPoints($patent->item, $patent->auth_count, $patent->place);
			$patent->save();
			$rating = ($rating = Rating::findOne(['user_id' => Yii::$app->user->id])) ? $rating :new Rating();
			$summ = Patent::find()
				->where(['user_id' => Yii::$app->user->id])
				->sum('points');
			$rating->ppatent = $summ;
			$rating->save();			
			$rating->prating = Rating::updatePRating(Yii::$app->user->id);
			$rating->save();
		}
	}
	
	public function setItemText($item)
	{
		if($item == 1){
			$this->item_text = 'Патенти на корисні моделі';
		} elseif($item == 2){
			$this->item_text = 'Патенти на винаходи або на промислові зразки';
		}
	}
	
	public function setPoints($item, $auth, $place)
	{
		if($item == 1){
			$this->points = $place * 4 / $auth;
		} elseif($item == 2){
			$this->points = $place * 8 / $auth;
		}
	}

	public static function setSumAfterDel()
	{
		$rating = Rating::findOne(['user_id' => Yii::$app->user->id]);
		$summ = Patent::find()
			->where(['user_id' => Yii::$app->user->id])
			->sum('points');
		$rating->ppatent = $summ;
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
        return new PatentQuery(get_called_class());
    }

    public function search($params)
    {
        $query = Patent::find();

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
