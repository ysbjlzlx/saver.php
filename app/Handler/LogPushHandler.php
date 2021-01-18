<?php

namespace App\Handler;

use GuzzleHttp\Client;
use Monolog\Handler\AbstractProcessingHandler;

class LogPushHandler extends AbstractProcessingHandler
{
    protected function write(array $record): void
    {
        if ('true' != $_ENV['LOG_PUSH'] || !isset($_ENV['SERVER_CHAN_KEY']) || '' == $_ENV['SERVER_CHAN_KEY']) {
            return;
        }
        $options = [
            'form_params' => [
                'text' => $record['message'].time(),
                'desp' => $this->makeDescription($record),
            ],
        ];
        $client = new Client(['base_uri' => 'http://sc.ftqq.com']);
        $client->post("{$_ENV['SERVER_CHAN_KEY']}.send", $options);
    }

    private function makeDescription(array $record): string
    {
        $content = "level：{$record['level_name']}\r\n\r\n";
        $content .= "message：{$record['message']}\r\n\r\n";

        return $content;
    }
}
