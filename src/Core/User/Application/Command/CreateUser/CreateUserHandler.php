<?php

namespace App\Core\User\Application\Command\CreateUser;

use App\Core\User\Domain\Exception\UserAlreadyExistsException;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use App\Core\User\Domain\User;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CreateUserHandler
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function __invoke(CreateUserCommand $command): void
    {
        if ($this->userRepository->getByEmail($command->email)) {
            throw new UserAlreadyExistsException('Użytkownik o podanym adresie email juz istnieje');
        }
        $this->userRepository->save(new User(
            $command->email,
        ));

        $this->userRepository->flush();
    }
}
