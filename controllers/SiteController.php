<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\User;
use app\models\Profile;
use app\models\RatingaddForm;
use app\models\Monograph;
use app\models\Article2;
use app\models\Article6;
use app\models\Patent;
use app\models\Reports;
use app\models\Research;
use app\models\Training;
use app\models\Studwork;
use app\models\Victory;
use app\models\Other;
use app\models\Rating;
use app\models\SignupForm;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'ratingadd', 
						'monograph', 'article2', 'article6', 
						'patent', 'reports', 'research', 
						'other', 'victory', 'studwork', 
						'training'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
					[
                        'actions' => ['logout', 'ratingadd', 
						'monograph', 'article2', 'article6', 
						'patent', 'reports', 'research', 
						'other', 'victory', 'studwork', 
						'training'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

	public function actionSignup()
    {
		$model = new SignupForm();
		
        if ($model->load(Yii::$app->request->post())) {
			if ($user = $model->signup()) {
                Yii::$app->getSession()->setFlash('success', 'Реєстрація пройшла успішно.');
                return $this->goHome();
            }
        }
		return $this->render('signup', [
            'model' => $model,
        ]);
	}
	
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
	
	public function actionRatingadd()
    {
		$model = ($model = Profile::findOne(Yii::$app->user->id)) ? $model : new Profile();
		if($model->load(Yii::$app->request->post()) && $model->validate()){
			if($model->updateProfile()){
				Yii::$app->session->setFlash('success', 'Профіль змінено');
			} else {
				 Yii::$app->session->setFlash('error', 'Профіль не змінено');
                Yii::error('Помилка запису. Профіль не змінено');
                return $this->refresh();
			}
		}
		return $this->render(
			'ratingadd',
			[
				'model' => $model
			]
		);
    }

	public function actionMonograph()
	{
		$model = new Monograph();
		if($model->load(Yii::$app->request->post()) && $model->validate()){
			if($model->addMonograph()){
				Yii::$app->session->setFlash('success', 'Данні додано');
				return $this->goBack();
			} else {
				 Yii::$app->session->setFlash('error', 'Данні не додано');
                Yii::error('Помилка запису. Данні не додано');
                return $this->refresh();
			}
		}
		$dataProvider = $model->search(Yii::$app->request->queryParams);

		return $this->render(
			'monograph',
			[
				'model' => $model,
				'dataProvider' => $dataProvider,
			]
		);
	}

	public function actionDeleteMonograph($id)
    {
		if($model = Monograph::findOne($id)) {
			$model->delete();
			Monograph::setSumAfterDel();
        }

		return $this->redirect(['/site/monograph#post']);
    }

	public function actionArticle2()
	{
		$model = new Article2();
		if($model->load(Yii::$app->request->post()) && $model->validate()){
			if($model->addArticle2()){
				Yii::$app->session->setFlash('success', 'Данні додано');
				return $this->goBack();
			} else {
				 Yii::$app->session->setFlash('error', 'Данні не додано');
                Yii::error('Помилка запису. Данні не додано');
                return $this->refresh();
			}
		}
		$dataProvider = $model->search(Yii::$app->request->queryParams);
				
		return $this->render(
			'article2',
			[
				'model' => $model,
				'dataProvider' => $dataProvider,
			]
		);
	}
	
	public function actionDeleteArticle2($id)
    {
		if($model = Article2::findOne($id)) {
            $model->delete();
			Article2::setSumAfterDel();
        }
        
		return $this->redirect(['/site/article2#post']);
    }

	public function actionArticle6()
	{
		$model = new Article6();
		if($model->load(Yii::$app->request->post()) && $model->validate()){
			if($model->addArticle6()){
				Yii::$app->session->setFlash('success', 'Данні додано');
				return $this->goBack();
			} else {
				 Yii::$app->session->setFlash('error', 'Данні не додано');
                Yii::error('Помилка запису. Данні не додано');
                return $this->refresh();
			}
		}
		$dataProvider = $model->search(Yii::$app->request->queryParams);
				
		return $this->render(
			'article6',
			[
				'model' => $model,
				'dataProvider' => $dataProvider,
			]
		);
	}
	
	public function actionDeleteArticle6($id)
    {
		if($model = Article6::findOne($id)) {
            $model->delete();
			Article6::setSumAfterDel();
        }
        
		return $this->redirect(['/site/article6#post']);
    }

	public function actionPatent()
	{
		$model = new Patent();
		if($model->load(Yii::$app->request->post()) && $model->validate()){
			if($model->addPatent()){
				Yii::$app->session->setFlash('success', 'Данні додано');
				return $this->goBack();
			} else {
				 Yii::$app->session->setFlash('error', 'Данні не додано');
                Yii::error('Помилка запису. Данні не додано');
                return $this->refresh();
			}
		}
		
		$dataProvider = $model->search(Yii::$app->request->queryParams);
				
		return $this->render(
			'patent',
			[
				'model' => $model,
				'dataProvider' => $dataProvider,
			]
		);
	}
	
	public function actionDeletePatent($id)
    {
		if($model = Patent::findOne($id)) {
            $model->delete();
			Patent::setSumAfterDel();
        }
        
		return $this->redirect(['/site/patent#post']);
    }

	public function actionReports()
	{
		$model = new Reports();
		if($model->load(Yii::$app->request->post()) && $model->validate()){
			if($model->addReports()){
				Yii::$app->session->setFlash('success', 'Данні додано');
				return $this->goBack();
			} else {
				 Yii::$app->session->setFlash('error', 'Данні не додано');
                Yii::error('Помилка запису. Данні не додано');
                return $this->refresh();
			}
		}
		
		$dataProvider = $model->search(Yii::$app->request->queryParams);
				
		return $this->render(
			'reports',
			[
				'model' => $model,
				'dataProvider' => $dataProvider,
			]
		);
	}
	
	public function actionDeleteReports($id)
    {
		if($model = Reports::findOne($id)) {
            $model->delete();
			Reports::setSumAfterDel();
        }
        
		return $this->redirect(['/site/reports#post']);
    }

	public function actionResearch()
	{
		$model = new Research();
		if($model->load(Yii::$app->request->post()) && $model->validate()){
			if($model->addResearch()){
				Yii::$app->session->setFlash('success', 'Данні додано');
				return $this->goBack();
			} else {
				 Yii::$app->session->setFlash('error', 'Данні не додано');
                Yii::error('Помилка запису. Данні не додано');
                return $this->refresh();
			}
		}
		
		$dataProvider = $model->search(Yii::$app->request->queryParams);
				
		return $this->render(
			'research',
			[
				'model' => $model,
				'dataProvider' => $dataProvider,
			]
		);
	}
	
	public function actionDeleteResearch($id)
    {
		if($model = Research::findOne($id)) {
            $model->delete();
			Research::setSumAfterDel();
        }
        
		return $this->redirect(['/site/research#post']);
    }

	public function actionTraining()
	{
		$model = new Training();
		if($model->load(Yii::$app->request->post()) && $model->validate()){
			if($model->addTraining()){
				Yii::$app->session->setFlash('success', 'Данні додано');
				return $this->goBack();
			} else {
				 Yii::$app->session->setFlash('error', 'Данні не додано');
                Yii::error('Помилка запису. Данні не додано');
                return $this->refresh();
			}
		}		
		
		$dataProvider = $model->search(Yii::$app->request->queryParams);
				
		return $this->render(
			'training',
			[
				'model' => $model,
				'dataProvider' => $dataProvider,
			]
		);
	}
	
	public function actionDeleteTraining($id)
    {
		if($model = Training::findOne($id)) {
            $model->delete();
			Training::setSumAfterDel();
        }
        
		return $this->redirect(['/site/training#post']);
    }

	public function actionStudwork()
	{
		$model = new Studwork();
		if($model->load(Yii::$app->request->post()) && $model->validate()){
			if($model->addStudwork()){
				Yii::$app->session->setFlash('success', 'Данні додано');
				return $this->goBack();
			} else {
				 Yii::$app->session->setFlash('error', 'Данні не додано');
                Yii::error('Помилка запису. Данні не додано');
                return $this->refresh();
			}
		}		
		
		$dataProvider = $model->search(Yii::$app->request->queryParams);
				
		return $this->render(
			'studwork',
			[
				'model' => $model,
				'dataProvider' => $dataProvider,
			]
		);
	}
	
	public function actionDeleteStudwork($id)
    {
		if($model = Studwork::findOne($id)) {
            $model->delete();
			Studwork::setSumAfterDel();
        }
        
		return $this->redirect(['/site/studwork#post']);
    }

	public function actionVictory()
	{
		$model = new Victory();
		if($model->load(Yii::$app->request->post()) && $model->validate()){
			if($model->addVictory()){
				Yii::$app->session->setFlash('success', 'Данні додано');
				return $this->goBack();
			} else {
				 Yii::$app->session->setFlash('error', 'Данні не додано');
                Yii::error('Помилка запису. Данні не додано');
                return $this->refresh();
			}
		}
				
		$dataProvider = $model->search(Yii::$app->request->queryParams);
				
		return $this->render(
			'victory',
			[
				'model' => $model,
				'dataProvider' => $dataProvider,
			]
		);
	}
	
	public function actionDeleteVictory($id)
    {
		if($model = Victory::findOne($id)) {
            $model->delete();
			Victory::setSumAfterDel();
        }
        
		return $this->redirect(['/site/victory#post']);
    }

	public function actionOther()
	{
		$model = new Other();
		if($model->load(Yii::$app->request->post()) && $model->validate()){
			if($model->addOther()){
				Yii::$app->session->setFlash('success', 'Данні додано');
				return $this->goBack();
			} else {
				 Yii::$app->session->setFlash('error', 'Данні не додано');
                Yii::error('Помилка запису. Данні не додано');
                return $this->refresh();
			}
		}
		$dataProvider = $model->search(Yii::$app->request->queryParams);
				
		return $this->render(
			'other',
			[
				'model' => $model,
				'dataProvider' => $dataProvider,
			]
		);
	}
	
	public function actionDeleteOther($id)
    {
		if($model = Other::findOne($id)) {
            $model->delete();
			Other::setSumAfterDel();
        }
        
		return $this->redirect(['/site/other#post']);
    }

	public function actionRating()
	{
		$model = new Rating();
		$dataProvider = $model->search(Yii::$app->request->queryParams);
				
		return $this->render(
			'rating',
			[
				'model' => $model,
				'dataProvider' => $dataProvider,
			]
		);
	}
}
