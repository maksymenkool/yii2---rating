<?php

/* @var $form yii\widgets\ActiveForm */
/* @var $model app\models\SignupForm */

use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\user\Module;//??

$this->title = 'Реєстрація';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

<?php
	$form = ActiveForm::begin([
        'id' => 'signup',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]);?>
	<?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
	<?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
	<?= $form->field($model, 'password')->passwordInput() ?>
	<?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                'template' => '<div class="row"><div class="col-lg-6">{image}</div><div class="col-lg-6">{input}</div></div>',
]) ?>

		<div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Зареєструвати', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

<?php
	ActiveForm::end();
?>
</div>
