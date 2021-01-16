<?php

namespace App\Handler;

use App\Model\LogModel;
use Monolog\DateTimeImmutable;
use Monolog\Handler\AbstractProcessingHandler;

class DatabaseHandler extends AbstractProcessingHandler
{
    protected function write(array $record): void
    {
        $datetime = $record['datetime'];
        assert($datetime instanceof DateTimeImmutable);

        $logModel = new LogModel();
        $logModel->message = $record['message'];
        $logModel->context = $record['context'];
        $logModel->level = $record['level'];
        $logModel->level_name = $record['level_name'];
        $logModel->channel = $record['channel'];
        $logModel->datetime = $datetime->format(DateTimeImmutable::ATOM);
        $logModel->extra = $record['extra'];
        $logModel->save();
    }
}
