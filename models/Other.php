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
class OtherQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Other[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Other|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

/**
 * This is the model class for table "other".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $item
 * @property string $item_text
 * @property integer $place
 * @property string $name
 * @property double $points
 *
 * @property User $user
 */
class Other extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'other';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item', 'name', 'place'], 'required'],
            [['user_id', 'item', 'place'], 'integer'],
            [['item_text','name'], 'string'],
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
			'place' => 'Місце участі',
			'item' => 'Оберіть необхідне',
			'item_text' => 'Вид діяльності',
            'name' => 'Назва',
			'points' => 'Бали',
        ];
    }

	public function addOther()
	{
		if ($this->validate()) {
			$other = new Other();
			$other->user_id = Yii::$app->user->id;
			$other->item = $this->item;
			$other->setItemText($other->item);
			$other->name = $this->name;
			$other->place = $this->place;
			$other->setPoints($other->item, $other->place);
			$other->save();			
			$rating = ($rating = Rating::findOne(['user_id' => Yii::$app->user->id])) ? $rating :new Rating();
			$item = [1, 2, 3];
			$summ1 = Other::find()
				->where(['user_id' => Yii::$app->user->id, 'item' => $item, ])
				->sum('points');
			$summ4 = Other::find()
				->where(['user_id' => Yii::$app->user->id, 'item' => 4])
				->sum('points');
			$summ5 = Other::find()
				->where(['user_id' => Yii::$app->user->id, 'item' => 5])
				->sum('points');
			if($summ4 > 5){
				$summ4 = 5;
			}
			if($summ5 > 20){
				$summ5 = 20;
			}
			$rating->pother = $summ1 + $summ4 + $summ5;
			$rating->save();			
			$rating->prating = Rating::updatePRating(Yii::$app->user->id);
			$rating->save();
		}
	}

	public function setItemText($item)
	{
		if($item == 1){
			$this->item_text = 'Опанування дисертацій на здобуття наукового ступеня';
		} elseif($item == 2){
			$this->item_text = 'Написання відгуків на автореферати дисертацій на здобуття наукового ступеня';
		} elseif($item == 3){
			$this->item_text = 'Участь у роботі оргкомітетів конференцій';
		} elseif($item == 4){
			$this->item_text = 'Співробітництво з представниками малої академії наук України';
		} elseif($item == 5){
			$this->item_text = 'Участь у громадських заходах та прийняття активної участі у житті НТУ «ХПІ»';
		}
	}
		
	public function setPoints($item, $place)
	{
		if($item == 1){
			$this->points = 15;
		} elseif($item == 2){
			$this->points = 2;
		} elseif($item == 3){
			if($place == 1){
				$k = 1;
			} elseif($place == 2){
				$k = 2;
			}
			$this->points = 8 * $k;
		} elseif($item == 4){
			$this->points = 5;
		} elseif($item == 5){
			$this->points = 20;
		}
	}

	public static function setSumAfterDel()
	{
		$rating = Rating::findOne(['user_id' => Yii::$app->user->id]);
		$item = [1, 2, 3];
			$summ1 = Other::find()
				->where(['user_id' => Yii::$app->user->id, 'item' => $item])
				->sum('points');
		$summ4 = Other::find()
			->where(['user_id' => Yii::$app->user->id, 'item' => 4])
			->sum('points');
		$summ5 = Other::find()
			->where(['user_id' => Yii::$app->user->id, 'item' => 5])
			->sum('points');
		if($summ4 > 5){
			$summ4 = 5;
		}
		if($summ5 > 20){
			$summ5 = 20;
		}
		$rating->pother = $summ1 + $summ4 + $summ5;
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
        return new OtherQuery(get_called_class());
    }
	
	
    public function search($params)
    {
        $query = Other::find();

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
