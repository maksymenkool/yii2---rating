<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class SignupForm extends Model
{
    public $username;
	public $email;
	public $password;
	public $verifyCode;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
			['username', 'required'],
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
 
            ['verifyCode', 'captcha'],
        ];
    }
	
	public function attributeLabels(){
		return [
			'username' => 'Нікнейм',
			'email' => 'Email',
			'password' => 'Пароль',
			'verifyCode' => 'Код перевірки'
		];
	}

    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
			$user->username = $this->username;			
			$user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
 
            return $user->save();
        } 
        return null;
    }
}
