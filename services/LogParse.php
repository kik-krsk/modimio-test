<?php

namespace app\services;

use app\models\Log;
use app\models\UserAgent;
use DateTime;
use Kassner\LogParser\LogParser;
use Kassner\LogParser\SimpleLogEntry;

class LogParse
{
    /**
     * Формат лога в файле
     * https://github.com/kassner/log-parser
     *
     * @var string
     */
    public string $format = '%h %l %u %t "%m %U %H" %>s %O "%{Referer}i" \"%{User-Agent}i"';

    public function __construct(?string $format = null)
    {
        $this->format = $format ?? $this->format;
    }

    /**
     * Парсим файл. Возвращаем массив не обработанных строк.
     *
     * @param string $fileName
     *
     * @return array
     */
    public function parseFile(string $fileName)
    {
        $errors = [];
        $parser = new LogParser($this->format);
        $lines = file($fileName, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $userAgentParse = new UserAgentParse();
        foreach ($lines as $num => $line) {
            try {
                $data = $parser->parse($line);
                $userAgent = $userAgentParse->parse($data->HeaderUserAgent);
                $this->saveData($data, $userAgent);
            } catch (\Exception $e) {
                $errors[$num] = $e->getMessage();
            }
        }

        return $errors;
    }

    /**
     * saveData.
     *
     * @param SimpleLogEntry $data
     * @param StdClass       $userAgentData
     *
     * @throws \yii\db\Exception
     */
    protected function saveData(SimpleLogEntry $data, $userAgentData)
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $userAgent = new UserAgent();
            $userAgent->full = $data->HeaderUserAgent;
            $userAgent->os = $userAgentData->platform;
            $userAgent->arch = $userAgentData->browser_bits;
            $userAgent->browser = $userAgentData->browser;
            $userAgent->save();
            $log = new Log();
            $log->ip = $data->host;
            $date = new DateTime($data->time);
            $log->date = $date->format('Y-m-d H:i:s.u');
            $log->url = $data->URL;
            $log->link('userAgent', $userAgent);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();

            throw new \yii\db\Exception($e->getMessage());
        }
    }
}
