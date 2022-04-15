<?php

namespace app\commands;

use app\services\LogParse;
use yii\console\Controller;
use yii\console\Exception;

class ImportLogController extends Controller
{
    /**
     * Парсит и загружает в базу из файл лога. Первым параметром передаем путь к файлу
     *
     * @param  mixed $fileName
     * @return void
     */
    public function actionIndex(string $fileName)
    {
        if (!file_exists($fileName)) {
            throw new Exception('Файл не найден');
        }

        $parse = new LogParse();
        $errors = $parse->parseFile($fileName);
        foreach ($errors as $num => $error) {
            echo 'В строке . ' . $num . ' ошибка: ' . $error . PHP_EOL;
        }
    }
}
