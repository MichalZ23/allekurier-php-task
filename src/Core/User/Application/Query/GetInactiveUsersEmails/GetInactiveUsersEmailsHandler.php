<?php

namespace App\Core\User\Application\Query\GetInactiveUsersEmails;

use App\Core\User\Application\DTO\UserDTO;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use App\Core\User\Domain\User;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class GetInactiveUsersEmailsHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    /**
     * @return UserDTO[]
     */
    public function __invoke(GetInactiveUsersEmailsQuery $query): array
    {
        $users = $this->userRepository->getUsersByActivityStatus(false);

        return array_map(
            static fn(User $user): UserDTO => new UserDTO($user->getEmail()),
            $users,
        );
    }
}
