<?php

use yii\helpers\Html;
use app\modules\user\Module;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\Patent */
/* @var $form ActiveForm */
$this->title = 'Отримання охоронних документів';
$this->params['breadcrumbs'][] = ['label' => 'Форма оцінювання', 'url' => ['ratingadd']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-profile-rating">

    <h1><?= Html::encode($this->title) ?></h1>

<div class="patent">

    <?php $form = ActiveForm::begin([
        'id' => 'patent',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-8\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]);?>
		<?= $form->field($model, 'item')->dropDownList([
		'0' => '',
		'1' => 'Патенти на корисні моделі',
		'2' => 'Патенти на винаходи або на промислові зразки'
		]); ?>
		<?= $form->field($model, 'name')->textarea() ?>
        <?= $form->field($model, 'auth_count') ?>
        <?= $form->field($model, 'place')->radioList(
			[1 => ' видання в Україні', 2 => ' видання за кордоном'],
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
				<br><b><a href="ratingadd#post2_1">Перейти до наступного пункту "Форми оцінювання"</a></b></br>
            </div>
        </div>
		<p id="post"><h2> Перевірте ваші данні: </h2></p>
		<p><em>Місце видання:<br />1 - в Україні;  2 - за кордоном.</em></p>
		
		<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'item_text',
			'name',
			'auth_count',
			'place',
            'points',
			[
			'content' => function ($model) {
                return \yii\bootstrap\Html::a('<span class="glyphicon glyphicon-trash delete"></span>', ['/site/delete-patent', 'id' => $model->id]);
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

</div><!-- patent1 -->
</div>
