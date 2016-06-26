<?php

use yii\helpers\Html;
use app\modules\user\Module;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Other */
/* @var $form ActiveForm */
$this->title = 'Інше';
$this->params['breadcrumbs'][] = ['label' => 'Форма оцінювання', 'url' => ['ratingadd']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-profile-rating">

    <h1><?= Html::encode($this->title) ?></h1>

<div class="other">

    <?php $form = ActiveForm::begin([
        'id' => 'other',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-6\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]);?>
		<?= $form->field($model, 'item')->dropDownList([
		'0' => '',
		'1' => 'Опанування дисертацій на здобуття наукового ступеня',
		'2' => 'Написання відгуків на автореферати дисертацій на здобуття наукового ступеня',
		'3' => 'Участь у роботі оргкомітетів конференцій',
		'4' => 'Співробітництво з представниками малої академії наук України',
		'5' => 'Участь у громадських заходах та прийняття активної участі у житті НТУ «ХПІ»'
	]); ?>
		<?= $form->field($model, 'name')->textarea() ?>
		<?= $form->field($model, 'place')->radioList(
			[1 => ' вітчизняні НДР', 2 => ' міжнародні НДР'],
			['item' => function($index, $label, $name, $checked, $value) {
                    $return = '<label class="modal-radio">';
                    $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" tabindex="3">';
                    $return .= '<span>' . ucwords($label) . '</span>';
                    $return .= '</label><br />';
                    return $return;
            }]); ?>			
        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-11">
                <?= Html::submitButton('Зберегти', ['class' => 'btn btn-primary']) ?>
				<br />
				<br><b><a href="rating">Переглянути рейтинг науковців</a></b></br>
				<br><b><a href="ratingadd#post8">Повернутися до "Форми оцінювання"</a></b></br>
            </div>
        </div>
		<p id="post"><h2> Перевірте ваші данні: </h2></p>
		<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'item_text',
            'name',
            'points',
			[
			'content' => function ($model) {
                return \yii\bootstrap\Html::a('<span class="glyphicon glyphicon-trash delete"></span>', ['/site/delete-other', 'id' => $model->id]);
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

</div><!-- reports -->
</div>
