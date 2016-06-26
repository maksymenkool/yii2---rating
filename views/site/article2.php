<?php

use yii\helpers\Html;
use app\modules\user\Module;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\Article2 */
/* @var $form ActiveForm */
$this->title = 'Статті';
$this->params['breadcrumbs'][] = ['label' => 'Форма оцінювання', 'url' => ['ratingadd']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-profile-rating">

    <h1><?= Html::encode($this->title) ?></h1>

<div class="article2">

    <?php $form = ActiveForm::begin([
        'id' => 'article2',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-8\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]);?>
		<?= $form->field($model, 'item')->dropDownList([
		'1' => '',
		'2' => 'Статті в журналах, що входять до міжнародних науково метричних баз даних (SCOPUS, Webometrics  та ISI Master Journal List)',
		'3' => 'Статті в журналах, що написані англійською або німецькою мовами та надруковані закордоном',
		'4' => 'Статті в журналах, що вийшли за кордоном але написані українською або рос. мовами  або статті англійською мовою, що надруковані в Україні',
		'5' => 'Статті у фахових виданнях України'
		]); ?>
		<?= $form->field($model, 'name')->textarea() ?>
		<?= $form->field($model, 'auth_count') ?>
        <?= $form->field($model, 'pages_count')->radioList(
			[1 => ' кількість сторінок статті до 4-х включно',
             2 => ' кількість сторінок статті від 5 до 8-ми включно',
			 3 => ' кількість сторінок більше 8-ми'],
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
				<br><b><a href="ratingadd#post1_5">Перейти до наступного пункту "Форми оцінювання"</a></b></br>
            </div>
        </div>
		<p id="post"><h2> Перевірте ваші данні: </h2></p>
		<p><em>Кількість сторінок:<br />1 - до 4-х включно;  2 - від 5 до 8-ми включно;  3 - більше 8-ми.</em></p>
		
		<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'item_text',
            'name',
			'pages_count',
			'auth_count',
            'points',
			[
			'content' => function ($model) {
                return \yii\bootstrap\Html::a('<span class="glyphicon glyphicon-trash del"></span>', ['/site/delete-article2', 'id' => $model->id]);
            }
			],
        ],
		]); ?>
	<?php
		$this->registerJS("
			$('.del').click(function(){
				if(!confirm('Ви впевнені, що хочете видалити цей елемент?')){
					return false;
				}
			});
		");
	?>
    <?php ActiveForm::end(); ?>

</div><!-- article2 -->
</div>
