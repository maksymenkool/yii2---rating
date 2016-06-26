<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $auth_key
 * @property string $password
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Profile $profile
 */
class User extends ActiveRecord implements IdentityInterface
{
	
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
			['username', 'required'],
			['username', 'match', 'pattern' => '#^[\w_-]+$#i'],
            ['username', 'string', 'min' => 2, 'max' => 255],
			['username', 'unique',
                'targetClass' => User::className(),
                'message' => 'Користувач з таким ім\'ям вже зареєстрований.'],
			
			['email','filter', 'filter' => 'trim'],
			['email', 'required'],
			['email', 'email'],
            ['email', 'unique',
				'targetClass' => User::className(),
				'message' => 'Цей email вже зареєстрований.'],

			['password','filter', 'filter' => 'trim'],
			['password', 'required'],
            ['password', 'string', 'min' => 6, 'max' => 255],
 
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Нікнейм',
            'email' => 'Email',
            'password_hash' => 'Пароль',
            'created_at' => 'Дата створення',
            'updated_at' => 'Дата зміни',
        ];
    }

	    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

	/** Находит пользователя по имени и возвращает объект найденного пользователя.
     *  Вызываеться из модели LoginForm.
     * Находит пользователя по username
     *
     * @param string $username
     * @return static|null
     */
	public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }
	
	/* Находит пользователя по емайл */
    public static function findByEmail($email)
    {
        return static::findOne([
            'email' => $email
        ]);
    }
	
	/**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('findIdentityByAccessToken is not implemented.');
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

	public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
	
    /**
     * Генерирует случайную строку из 32 шестнадцатеричных символов и присваивает (при записи) полученное значение полю auth_key
     * таблицы user для нового пользователя.
     * Вызываеться из модели SignupForm.
     */
	/**
     * Generates "remember me" authentication key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

	/**
     * Генерирует хеш из введенного пароля и присваивает (при записи) полученное значение полю password_hash таблицы user для
     * нового пользователя.
     * Вызываеться из модели SignupForm.
     */
    /**
     * @param string $password
     */
	public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }
	
	/**
     * Сравнивает полученный пароль с паролем в поле password_hash, для текущего пользователя, в таблице user.
     * Вызываеться из модели LoginForm.
     */
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
	public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }
}
