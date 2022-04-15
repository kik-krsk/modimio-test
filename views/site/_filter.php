<?php

/**
 * @var yii\web\View          $this
 * @var app\models\FilterForm $model
 */

use kartik\daterange\DateRangePicker;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\Url;

$form = ActiveForm::begin(['action' => Url::to(['site/filter']), 'method' => 'get', 'options' => ['class' => 'form-inline']]);

echo Html::beginTag('div', ['class' => 'form-group mb-2']);

    echo $form->field($model, 'dateRange', [
        'options' => ['class' => 'drp-container'],
    ])->widget(DateRangePicker::class, [
        'convertFormat' => true,
        'startAttribute' => 'startDate',
        'endAttribute' => 'stopDate',
        'pluginOptions' => [
            'locale' => ['format' => 'Y-m-d'],
            'autoApply' => true,
        ],
        'options' => ['placeholder' => 'Выбирите дату'],
    ])->label(false);
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'form-group mb-2']);
    echo $form->field($model, 'os')->textInput(['class' => 'form-control mx-sm-3', 'placeholder' => 'Введите ОС'])->label(false);
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'form-group mb-2']);
    echo $form->field($model, 'arch')->textInput(['class' => 'form-control mx-sm-3', 'placeholder' => 'Введите Архитектуру'])->label(false);
echo Html::endTag('div');

?>
<?php echo Html::submitButton('Фильтровать', ['class' => 'btn btn-primary mb-2']); ?>
<?php
ActiveForm::end();
