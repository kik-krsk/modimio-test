<?php

use yii\data\ArrayDataProvider;

use yii\grid\GridView;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'date',
        'requestCount',
        'url',
        'browser',
    ],
]);
