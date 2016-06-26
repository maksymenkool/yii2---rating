<?php

namespace app\models;

use Yii;

use app\models\User;
use app\models\Rating;
use yii\data\ActiveDataProvider;

/**
 * This is the ActiveQuery class for [[Profile]].
 *
 * @see Profile
 */
class ProfileQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Profile[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Profile|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

/**
 * This is the model class for table "profile".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $first_name
 * @property string $second_name
 * @property string $middle_name
 * @property integer $age
 * @property string $department
 * @property integer $position
 * @property string $position_text
 *
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['first_name', 'second_name', 'middle_name', 'age', 'department', 'position'], 'required'],
            [['user_id', 'age', 'position'], 'integer'],
            [['first_name', 'second_name', 'middle_name', 'department'], 'string', 'max' => 50],
			[['position_text'], 'string'],
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
            'first_name' => 'Прізвище',
            'second_name' => 'Ім\'я',
            'middle_name' => 'По батькові',
			'age' => 'Вік',
            'department' => 'Кафедра',
            'position' => 'Посада',
			'position_text' => 'Посада',
        ];
    }

	public function updateProfile()
	{
		if ($this->validate()) {
            $profile = ($profile = Profile::findOne(['user_id' => Yii::$app->user->id])) ? $profile : new Profile();
			$profile->user_id = Yii::$app->user->id;
			$profile->first_name = $this->first_name;			
			$profile->second_name = $this->second_name;
			$profile->middle_name = $this->middle_name;
			$profile->age = $this->age;
			$profile->department = $this->department;
			$profile->position = $this->position;
			$profile->setPosition($profile->position);
            $profile->save();
			$rating = ($rating = Rating::findOne(['user_id' => Yii::$app->user->id])) ? $rating :new Rating();
			$rating->user_id = Yii::$app->user->id;
			$name = $profile->first_name.' '.$profile->second_name.' '.$profile->middle_name;
			$rating->name = $name;
			$rating->save();
			return true;
        } 
        return null;
	}

	public function setPosition($position)
	{
		if($position == 1){
			$this->position_text = 'Аспірант';
		} elseif($position == 2){
			$this->position_text = 'Асистент';
		} elseif($position == 3){
			$this->position_text = 'Старший викладач';
		} elseif($position == 4){
			$this->position_text = 'Доцент';
		} elseif($position == 5){
			$this->position_text = 'Професор';
		} elseif($position == 6){
			$this->position_text = 'Докторант';
		}
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
