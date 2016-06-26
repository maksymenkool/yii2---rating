<?php

use yii\helpers\Html;
use app\modules\user\Module;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\Monograph */
/* @var $form ActiveForm */
$this->title = 'Mонографії';
$this->params['breadcrumbs'][] = ['label' => 'Форма оцінювання', 'url' => ['ratingadd']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-profile-rating">

    <h1><?= Html::encode($this->title) ?></h1>


<div class="monograph">

    <?php $form = ActiveForm::begin([
        'id' => 'monograph',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]);?>
        <?= $form->field($model, 'name')->textarea() ?>
		<?= $form->field($model, 'pages_count')->label('Кількість сторінок') ?>
        <?= $form->field($model, 'auth_count')->label('Кількість авторів') ?>
        <?= $form->field($model, 'lang')->radioList(
			[1 => ' Видання укр. або рос. мовами', 2 => ' Видання іноземною мовою'],
			['item' => function($index, $label, $name, $checked, $value) {
                    $return = '<label class="modal-radio">';
                    $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" tabindex="3">';
                    $return .= '<span>' . ucwords($label) . '</span>';
                    $return .= '</label><br />';
                    return $return;
                    }]
		)->label('Мова видання'); ?>		
        <?= $form->field($model, 'science')->radioList(
			[1 => ' В галузі економіко-гуманітарних наук', 2 => ' В галузі природних або технічних наук'],
			['item' => function($index, $label, $name, $checked, $value) {
                    $return = '<label class="modal-radio">';
                    $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" tabindex="3">';
                    $return .= '<span>' . ucwords($label) . '</span>';
                    $return .= '</label><br />';
                    return $return;
                    }]
		)->label('Галузь наук'); ?>        
    
        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-11">
                <?= Html::submitButton('Зберегти', ['class' => 'btn btn-primary']) ?>
				<br />
				<br><b><a href="rating">Переглянути рейтинг науковців</a></b></br>
				<br><b><a href="ratingadd#post1_1">Перейти до наступного пункту "Форми оцінювання"</a></b></br>
            </div>
        </div>
		<p id="post"><h2> Перевірте ваші данні: </h2></p>
		<p><em>Галузь Наук:<br />1 - в галузі економіко-гуманітарних наук; 2 - в галузі природних або технічних наук.</em></p>
		<p><em>Мова видання:<br />1 - видання укр. або рос. мовами; 2 - видання іноземною мовою.</em></p>
		<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			'name',
			'pages_count',
			'auth_count',
			'science',
			'lang',
            'points',
			[
			'content' => function ($model) {
                return \yii\bootstrap\Html::a('<span class="glyphicon glyphicon-trash delete"></span>', ['/site/delete-monograph', 'id' => $model->id]);
            }
			],
        ],
    ]); ?>
	<?php
		$this->registerJS("
			$('.delete').click(function(){
				if(!confirm('Ви впевнені, що хочете видалити цей елемент?')){
					return false;
				}
			});
		");
	?>
    <?php ActiveForm::end(); ?>

</div><!-- monograph -->
</div>
