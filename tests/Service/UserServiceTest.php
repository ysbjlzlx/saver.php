<?php

namespace Tests\Service;

use App\Service\UserService;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    private static $userService;

    public static function setUpBeforeClass(): void
    {
        self::$userService = new UserService();
    }

    public function testExistsByUsername()
    {
        $result = self::$userService->existsByUsername('admin');
        $this->assertFalse($result);
    }
}
