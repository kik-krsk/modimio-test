<?php

namespace app\models;

use DateTime;

class FilterForm extends \yii\base\Model
{
    public const MAX_INTERVAL = 1; //1 год
    public $dateRange;
    public $startDate;
    public $stopDate;
    public $os;
    public $arch;

    public function rules()
    {
        return [
            [['dateRange', 'os', 'arch', 'startDate', 'stopDate'], 'string'],
            [['dateRange', 'os', 'arch'], 'trim'],
            ['dateRange', 'validateRange'],
        ];
    }

    public function validateRange($attribute, $params)
    {
        $start = new DateTime($this->startDate);
        $stop = new DateTime($this->stopDate);
        $range = $start->diff($stop);
        if ($range->y >= self::MAX_INTERVAL) {
            $this->addError($attribute, 'Диапазон дат не должен быть больше года');
        }
    }
}
