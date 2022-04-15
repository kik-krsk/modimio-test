<?php

/**
 * @var yii\web\View $this
 */
$this->title = 'Результаты';
?>
<div class="site-index">
    <div class="filer">
        <?php echo $this->render('_filter', compact('model')); ?>
    </div>
    <div class="row mb-2">
        <div class="col-6">
            <?php echo $this->render('_grafic-count', compact('dateRequestCount')); ?>
        </div>

        <div class="col-6">

            <?php echo $this->render('_grafic-percent', compact('datePercentBrowser')); ?>
        </div>
    </div>
    <div class="table">
         <?php echo $this->render('_table', compact('dataProvider')); ?>
    </div>
</div>