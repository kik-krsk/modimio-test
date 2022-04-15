<?php

use dosamigos\chartjs\ChartJs;
use yii\helpers\ArrayHelper;
if (!empty($datePercentBrowser)):
$browsers = array_keys($datePercentBrowser['browsers']);
$dates = array_values($datePercentBrowser['dates']);
?>

<?php
echo ChartJs::widget([
    'type' => 'bar',
    'options' => [
        'height' => 400,
        'width' => 400,
    ],
    'data' => [
        'labels' => $dates,
        'datasets' => [
            [
                'label' => $browsers[0] ,
                'backgroundColor' => '#FFE8D6',
                'borderColor' => '#FFE8D6',
                'pointBackgroundColor' => '#FFE8D6',
                'pointBorderColor' => '#fff',
                'pointHoverBackgroundColor' => '#fff',
                'pointHoverBorderColor' => '#FFE8D6',
                'data' => ArrayHelper::getColumn($datePercentBrowser['browsers'][$browsers[0]], 'percent'),
            ],
            [
                'label' => $browsers[1],
                'backgroundColor' => '#B7B7A4',
                'borderColor' => '#B7B7A4',
                'pointBackgroundColor' => '#B7B7A4',
                'pointBorderColor' => '#fff',
                'pointHoverBackgroundColor' => '#fff',
                'pointHoverBorderColor' => '#B7B7A4',
                'data' => ArrayHelper::getColumn($datePercentBrowser['browsers'][$browsers[1]], 'percent'),
            ],
            [
                'label' => $browsers[2],
                'backgroundColor' => '#3F4238',
                'borderColor' => '#3F4238',
                'pointBackgroundColor' => '#3F4238',
                'pointBorderColor' => '#fff',
                'pointHoverBackgroundColor' => '#fff',
                'pointHoverBorderColor' => '#3F4238',
                'data' => ArrayHelper::getColumn($datePercentBrowser['browsers'][$browsers[2]], 'percent'),
            ],
        ],
    ],
]);
endif
?>