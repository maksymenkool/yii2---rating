<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Profile */
/* @var $form ActiveForm */

$this->title = 'Форма оцінювання';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-ratingadd">

    <h1><?= Html::encode($this->title) ?></h1>
	<p> Шановний учасник конкурсу!</p>
	<p> Для участі в рейтинговій системі скористайтесь посиланнями, щоб додати необхідну і наявну у вас інформацію.<br />
		Натискаючи "Зберегти" або "Додати" ви гарантуєте, що документи, які підтверджують достовірність наданної вами інформації, всі є в наявності і повністю відповідають вимогам!<br />
		Натискаючи "Зберегти" або "Додати" ви надаєте дозвіл на обробку особистих данних! <br /><br />
		<em>Підрозділ "Особисті данні" є обов'язковим для заповнення!</em></p><hr>
    
	<div> 
		<p><h3><b>Особисті данні</b></h3> </p>
		<?php
	$form = ActiveForm::begin([
        'id' => 'profile',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]);?>
	<?php
	if($model->user)
		echo $form->field($model->user, 'username');
	?>
	<?= $form->field($model, 'first_name') ?>
	<?= $form->field($model, 'second_name') ?>
	<?= $form->field($model, 'middle_name') ?>
			<?php
	//$form->field($model, 'birthday')->widget(DatePicker::classname(), [
    //'language' => 'ru',
		//'dateFormat' => 'dd-MM-yyyy',
	//]) 
			?>
	<?= $form->field($model, 'age') ?>
	<?= $form->field($model, 'department') ?>
	<?= $form->field($model, 'position')->dropDownList([
		'0' => '',
		'1' => 'Аспірант',
		'2' => 'Асистент',
		'3'=>'Старший викладач',
		'4' => 'Доцент',
		'5' => 'Професор',
		'6'=>'Докторант'
]); ?>	

		<div class="form-group">
            <div class="col-lg-offset-2 col-lg-11">
                <?= Html::submitButton('Зберегти', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

<?php
	ActiveForm::end();
?>
		<hr><p><h3><b>1. Наукові публікації</b></h3> </p>
	    <p><b>1.1 Mонографії</b> </p>
        <?= Html::a('Додати', ['monograph'], ['class' => 'btn btn-primary']) ?>
		<p id="post1_1"><br /> <em>Документ, який підтверджує достовірність:</em>
		<br /> Оригінал або копія першої та другої сторінок, завірені завідувачем кафедри та головою ради молодих вчених НТУ «ХПІ» <hr></p>

        <p><b>1.2 Статті в журналах, що входять до міжнародних науково метричних баз даних (SCOPUS, Webometrics  та ISI Master Journal List) </b><hr>
		<b>1.3 Статті в журналах, що написані англійською або німецькою мовами та надруковані закордоном</b><hr>
		<b>1.4 Статті в журналах, що вийшли за кордоном але написані українською або рос. мовами  або статті англійською мовою, що надруковані в Україні</b><hr>
		<b>1.5 Статті у фахових виданнях України</b></p>
        <p id="post1_5"><br /> <em>Документ, який підтверджує достовірність для пунктів 1.2 - 1.5:</em>
		<br /> Копія або оригінал наукової публікації, завірена завідувачем кафедри та головою ради молодих вчених НТУ «ХПІ»</p>
		<?= Html::a('Додати', ['article2'], ['class' => 'btn btn-primary']) ?><hr>

		<p><b>1.6 Статті у виданнях України, що не є фаховими, публікації в матеріалах (працях) всеукраїнської конференції, публікації в матеріалах (працях) міжнародних конференцій</b></p>
        <?= Html::a('Додати', ['article6'], ['class' => 'btn btn-primary']) ?>
        <p id="post1_6"><br /> <em>Документ, який підтверджує достовірність:</em>
		<br /> Копія або оригінал наукової публікації, завірена завідувачем кафедри та головою ради молодих вчених НТУ «ХПІ» <hr></p>

        <p><h3><b>2. Отримання охоронних документів :</b></h3> </p>
		<p><b>2.1 Патенти на корисні моделі<hr>2.2 Патенти на винаходи або на промислові зразки</b></p>
        <p id="post2_1"><br /> <em>Документ, який підтверджує достовірність для пунктів 2.1 - 2.2:</em>
		<br /> Копія охоронного документа (рішення про його видання), завірена завідувачем кафедри та головою ради молодих вчених НТУ «ХПІ»</p>
		<?= Html::a('Додати', ['patent'], ['class' => 'btn btn-primary']) ?> <hr>
        
        <p><h3><b>3. Доповіді на конференціях, симпозіумах :</b> </h3></p>
		<p><b> Виступи на всеукраїнських або на міжнародних конференціях</b></p>
        <?= Html::a('Додати', ['reports'], ['class' => 'btn btn-primary']) ?>
        <p id="post3"><br /> <em>Документ, який підтверджує достовірність:</em>
		<br /> Копія документа, який підтверджує участь у роботі конференції (копія посвідчення, сертифіката учасника конференції тощо), завірена завідувачем кафедри та головою ради молодих вчених НТУ «ХПІ» <hr></p>

        <p><h3><b>4. Участь у науково-дослідних роботах (НДР, госпдоговори, гранти) :</b></h3> </p>
		<p><b>Керівник, відповідальний виконавець або виконавець</b></p>
        <?= Html::a('Додати', ['research'], ['class' => 'btn btn-primary']) ?>
        <p id="post4"><br /> <em>Документ, який підтверджує достовірність:</em>
		<br /> Титульна сторінка та перелік виконавців науково-дослідної роботи з візою завідувача кафедри <hr></p>

        <p><h3><b>5. Підготовка висококваліфікованих наукових кадрів :</b></h3> </p>
		<p><b>5.1 Керівництво науковою роботою аспірантів або/та здобувачів</b></p>
        <?= Html::a('Додати', ['training'], ['class' => 'btn btn-primary']) ?>
        <p id="post5"><br /> <em>Документ, який підтверджує достовірність:</em>
		<br /> Перелік аспірантів або/та здобувачів з візою завідувача кафедри <hr></p>

        <p><h3><b>6. Робота зі студентами :</b> </h3></p>
		<p><b>6.1 Опублікування статей або тез в матеріалах (працях) конференцій під керівництвом учасника конкурсу «Кращий молодий науковець року» (автором є студент)</b></p>
        <?= Html::a('Додати', ['studwork'], ['class' => 'btn btn-primary']) ?>
        <p><br /> <em>Документ, який підтверджує достовірність:</em>
		<br /> Копія статті або тез із вказанням прізвища керівника та візою завідувача кафедри <hr></p>

		<p><b>6.2 Захисти кваліфікаційних магістерських робіт під керівництвом учасника конкурсу «Кращий молодий науковець року»</b></p>
        <?= Html::a('Додати', ['studwork'], ['class' => 'btn btn-primary']) ?>
        <p id="post6"><br /> <em>Документ, який підтверджує достовірність:</em>
		<br /> Перелік магістрів з візою завідувача кафедри<hr></p>

        <p><h3><b>7. Перемоги у конкурсах :</b></h3> </p>
		<p><b>7.1 Перемоги у виставках та ярмарках</b></p>
        <?= Html::a('Додати', ['victory'], ['class' => 'btn btn-primary']) ?>
        <p id="post7"><br /> <em>Документ, який підтверджує достовірність:</em>
		<br /> Копія диплому (грамоти) про перемогу у виставці або ярмарку, завірена завідувачем кафедри та головою ради молодих вчених НТУ «ХПІ» <hr></p>

        <p><h3><b>8. Інше :</b></h3> </p>
		<p><b>8.1 Опонування дисертацій на здобуття наукового ступеня</b></p>
        <p id="post8"> <em>Документ, який підтверджує достовірність:</em>
		Автореферат <hr></p>

        <p><b>8.2 Написання відгуків на автореферати дисертацій на здобуття наукового ступеня</b></p>
        <p> <em>Документ, який підтверджує достовірність:</em>
		Оригінал або копія відгуку, завірена вченим секретарем НТУ «ХПІ» <hr></p>

        <p><b>8.3 Участь у роботі оргкомітетів конференцій</b></p>
        <p> <em>Документ, який підтверджує достовірність:</em>
		<br /> Копія титульної сторінки (вихідні данні видання, список комітету), завірена завідувачем кафедри та головою ради молодих вчених НТУ «ХПІ» <hr></p>

		<p><b>8.4 Співробітництво з представниками малої академії наук України</b></p>
        <p> <em>Документ, який підтверджує достовірність:</em>
		<br /> Службова записка керівництва кафедри або факультету із зазначенням заходів в яких приймав участь конкурсант<hr></p>

        <p><b>8.5 Участь у громадських заходах та прийняття активної участі у житті НТУ «ХПІ»</b></p>
        <p> <em>Документ, який підтверджує достовірність:</em>
		 Службова записка керівництва НТУ «ХПІ» із зазначенням заходів в яких приймав участь конкурсант</p>
		<?= Html::a('Додати', ['other'], ['class' => 'btn btn-primary']) ?><hr>
		 
    </div>
    
   
</div>
