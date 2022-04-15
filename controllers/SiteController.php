<?php

namespace app\controllers;

use app\models\FilterForm;
use app\services\DataService;
use DateTime;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new FilterForm();
        $date = new DateTime();
        // $model->startDate = $date->format('Y-m-d');
        // $model->stopDate = $date->format('Y-m-d');
        // $model->dateRange = $date->format('Y-m-d');
        return $this->render('index', compact('model'));
    }

    /**
     * Результаты фильтрации.
     *
     * @return string
     */
    public function actionFilter()
    {
        $model = new FilterForm();
        $request = Yii::$app->request->get();
        if ($model->load($request) && $model->validate()) {
            $data = new DataService($model);
            $tableData = $data->getTableData();
            $dataProvider = new ArrayDataProvider(
                [
                    'allModels' => $tableData,
                    'sort' => [
                        'attributes' => [
                            'date',
                            'requestCount',
                            'url',
                            'browser',
                        ],
                    ],
                ]
            );
            if (!empty($tableData)) {
                $dateRequestCount = $data->getDateRequestCount();
                $datePercentBrowser = $data->getPercentBrowser();
            } else {
                $dateRequestCount = [];
                $datePercentBrowser = [];
            }

            return $this->render('result', compact('model', 'dateRequestCount', 'datePercentBrowser', 'dataProvider'));
        }

        return $this->render('index', compact('model'));
    }
}
