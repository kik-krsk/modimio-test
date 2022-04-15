<?php

namespace app\services;

use app\models\FilterForm;
use app\models\Log;
use DateTime;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class DataService
{
    /**
     * form.
     *
     * @var FilterForm
     */
    public $form;

    public function __construct(FilterForm $form)
    {
        $this->form = $form;
    }

    /**
     * Количество запросов по дням.
     *
     * @return array
     */
    public function getDateRequestCount()
    {
        $dateFormat = new Expression("DATE_FORMAT(date, '%Y-%m-%d')");
        $dateExpression = new Expression("{$dateFormat} as date");
        $countExpression = new Expression('COUNT(log.id) as count');
        $query = Log::find()->select([$dateExpression, $countExpression])->groupBy($dateFormat)->orderBy('date ASC');
        $query = $this->andFilter($query);

        return $query->asArray()->all();
    }

    /**
     * Получаем популярные браузеры.
     *
     * @param int $limit
     *
     * @return array
     */
    public function getTopBrowser($limit = 3)
    {
        $countExpression = new Expression('COUNT(*) as count');
        $query = Log::find()->select([$countExpression, 'browser'])->groupBy('browser')->orderBy('count DESC')->limit($limit);
        $query = $this->andFilter($query);

        return $query->asArray()->all();
    }

    /**
     * Запросы в % по самым популярным браузерам
     *
     * @return array
     */
    public function getPercentBrowser()
    {
        $countExpression = new Expression('count(*)  as count');
        $dateFormat = new Expression("DATE_FORMAT(date, '%Y-%m-%d')");
        $dateExpression = new Expression("{$dateFormat} as date");
        $query = Log::find()
            ->select([$countExpression, $dateExpression, 'browser'])
            ->groupBy(
                ['browser', $dateFormat]
            )->orderBy('date ASC')
        ;
        $query = $this->andFilter($query);
        $topBrowser = ArrayHelper::getColumn($this->getTopBrowser(), 'browser');
        $query->andWhere(['in', 'browser', $topBrowser]);
        $rows = $query->asArray()->all();
        $date = null;
        $result = [];
        foreach ($rows as $row) {
            if ($date !== $row['date']) {
                $date = new DateTime($row['date']);
                $date = $date->format('Y-m-d');
                $countAllDay = $this->getCountDayRequest($date);
                $result['dates'][] = $date;
            }
            $result['browsers'][$row['browser']][] =
            [
                'date' => $date,
                'count' => $row['count'],
                'all' => $countAllDay,
                'percent' => round($row['count'] / $countAllDay * 100, 2),
            ];
        }

        return $result;
    }

    /**
     * Данные для таблицы
     *
     * @return array
     */
    public function getTableData()
    {
        $rows = $this->getDateRequestCount();
        $result = [];
        foreach ($rows as $key => $row) {
            $url = $this->getTopColumnDay($row['date'], 'url');
            $browser = $this->getTopColumnDay($row['date'], 'browser');
            $result[] =
            [
                'date' => $row['date'],
                'requestCount' => $row['count'],
                'url' => $url['url'],
                'browser' => $browser['browser'],
            ];
        }
        return $result;
    }

    /**
     * Получаем кол-во за день.
     *
     * @param string $date ('YYYY-MM-DD')
     *
     * @return int
     */
    protected function getCountDayRequest($date)
    {
        $query = Log::find();
        $query = $this->andFilter($query, false);
        $dateExpression = new Expression("DATE_FORMAT(date, '%Y-%m-%d') = :date");
        $query->andWhere($dateExpression, ['date' => $date]);

        return $query->count();
    }

    /**
     * Добавляем фильтр
     *
     * @param Query $query
     * @param bool  $dateRange добавлять в условие dateRange
     *
     * @return Query
     */
    protected function andFilter($query, $dateRange = true)
    {
        $query->joinWith('userAgent');
        if (!empty($this->form->os)) {
            $query->andWhere(['like', 'os', $this->form->os]);
        }
        if (!empty($this->form->arch)) {
            $query->andWhere(['like', 'arch', $this->form->arch]);
        }
        if (!empty($this->form->dateRange) && $dateRange) {
            $start = new DateTime($this->form->startDate);
            $stop = new DateTime($this->form->stopDate);

            $query->andWhere(['between', 'date', $start->format('Y-m-d'), $stop->format('Y-m-d')]);
        }

        return $query;
    }

    /**
     * Получаем самое популярное за день.
     *
     * @param string $date   'Y-m-d'
     * @param string $column
     *
     * @return int
     */
    protected function getTopColumnDay($date, $column)
    {
        $query = Log::find();
        $dateFormat = new Expression("DATE_FORMAT(date, '%Y-%m-%d')");
        $countExpression = new Expression('Count(*) as count');
        $dateWhere = new Expression("DATE_FORMAT(date, '%Y-%m-%d') = :date");
        $dateSelect = new Expression("{$dateFormat} as date");
        $query->select([$dateSelect,$countExpression, $column])->groupBy($column)->orderBy('count DESC')->limit(1);
        $query->andWhere($dateWhere, ['date' => $date]);
        $query = $this->andFilter($query, false);

        return $query->asArray()->one();
    }
}
