<?php

namespace App\Event;

use App\Model\UserModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Psr\EventDispatcher\StoppableEventInterface;

class UserLoginEvent implements StoppableEventInterface
{
    /**
     * @var bool
     */
    private $isPropagationStopped = false;
    /**
     * @phpstan-template UserModel extends Model
     *
     * @var UserModel|Model|Builder
     */
    private $user;

    /**
     * UserLoginEvent constructor.
     *
     * @phpstan-template UserModel extends Model
     *
     * @param UserModel|Model|Builder $user 登录用户
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * @return UserModel|Model|Builder
     */
    public function getUser()
    {
        return $this->user;
    }

    public function isPropagationStopped(): bool
    {
        return $this->isPropagationStopped;
    }

    public function stopPropagation(): void
    {
        $this->isPropagationStopped = true;
    }
}
