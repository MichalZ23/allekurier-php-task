<?php

namespace App\Core\User\Application\Command\CreateUser;

final class CreateUserCommand
{
    public function __construct(
        public readonly string $email,
    ) {}
}
