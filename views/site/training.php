<?php

use yii\helpers\Html;
use app\modules\user\Module;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\Research */
/* @var $form ActiveForm */
$this->title = 'Підготовка висококваліфікованих наукових кадрів';
$this->params['breadcrumbs'][] = ['label' => 'Форма оцінювання', 'url' => ['ratingadd']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-profile-rating">

    <h1><?= Html::encode($this->title) ?></h1>
	<h3>5.1 Керівництво науковою роботою аспірантів або/та здобувачів</h3>
	<br />
<div class="training">

    <?php $form = ActiveForm::begin([
        'id' => 'training',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]);?>
		<?= $form->field($model, 'name')->textarea() ?>
        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-11">
                <?= Html::submitButton('Зберегти', ['class' => 'btn btn-primary']) ?>
				<br />
				<br><b><a href="rating">Переглянути рейтинг науковців</a></b></br>
				<br><b><a href="ratingadd#post5">Перейти до наступного пункту "Форми оцінювання"</a></b></br>
            </div>
        </div>
		<p id="post"><h2> Перевірте ваші данні: </h2></p>
		<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
			'points',
			[
			'content' => function ($model) {
                return \yii\bootstrap\Html::a('<span class="glyphicon glyphicon-trash delete"></span>', ['/site/delete-training', 'id' => $model->id]);
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
