<?php

use dosamigos\chartjs\ChartJs;
use yii\helpers\ArrayHelper;
?>
<?php
if (!empty($dateRequestCount)):
echo ChartJs::widget([
    'type' => 'line',
    'options' => [
        'height' => 400,
        'width' => 400,
    ],
    'data' => [
        'labels' => ArrayHelper::getColumn($dateRequestCount, 'date'),
        'datasets' => [
            [
                'label' => 'Всего запросов',
                'backgroundColor' => 'rgba(179,181,198,0.2)',
                'borderColor' => 'rgba(179,181,198,1)',
                'pointBackgroundColor' => 'rgba(179,181,198,1)',
                'pointBorderColor' => '#fff',
                'pointHoverBackgroundColor' => '#fff',
                'pointHoverBorderColor' => 'rgba(179,181,198,1)',
                'data' => ArrayHelper::getColumn($dateRequestCount, 'count'),
            ],
        ],
    ],
]);
endif ?>