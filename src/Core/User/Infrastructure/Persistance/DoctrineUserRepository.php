<?php

namespace App\Core\User\Infrastructure\Persistance;

use App\Common\EventManager\EventsCollectorDispatcher;
use App\Core\User\Domain\Repository\UserRepositoryInterface;
use App\Core\User\Domain\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;

class DoctrineUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly EventsCollectorDispatcher $eventsCollectorDispatcher,
    ) {}

    /**
     * @throws NonUniqueResultException
     */
    public function getByEmail(string $email): ?User
    {
        return $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.email = :user_email')
            ->setParameter(':user_email', $email)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->eventsCollectorDispatcher->dispatchEntityEvents($user);
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function getInactiveUsers(): array
    {
        return $this->entityManager->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.isActive = :is_active')
            ->setParameter('is_active', false)
            ->getQuery()
            ->getResult();
    }
}
