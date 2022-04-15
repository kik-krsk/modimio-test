<?php

/**
 * @var yii\web\View $this
 * @var app\models\FilterForm $model
 */
$this->title = 'Фильтр';
?>
<div class="filer">
    <?php echo $this->render('_filter', compact('model')); ?>
</div>