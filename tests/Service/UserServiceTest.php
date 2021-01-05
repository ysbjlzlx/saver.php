<?php

namespace Tests\Service;

use App\Service\UserService;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    /**
     * @var UserService
     */
    private static $userService;

    public static function setUpBeforeClass(): void
    {
        self::$userService = new UserService();
    }

    public function testExistsByUsername(): void
    {
        $result = self::$userService->existsByUsername('admin');
        $this->assertFalse($result);
    }
}
