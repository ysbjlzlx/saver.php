<?php

namespace Tests\Service\Ad;

use App\Service\Ad\AdPublisherService;
use PHPUnit\Framework\TestCase;

class AdPublisherServiceTest extends TestCase
{
    public function testIndex(): void
    {
        $service = new AdPublisherService();
        $result = $service->index(1, 0, ['username' => 'username']);
        echo json_encode($result);
    }
}
