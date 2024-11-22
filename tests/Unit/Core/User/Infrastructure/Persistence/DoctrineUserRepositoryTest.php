<?php

namespace App\Tests\Unit\Core\User\Infrastructure\Persistence;

use App\Core\User\Domain\Event\UserCreatedEvent;
use App\Core\User\Domain\User;
use App\Core\User\Infrastructure\Persistance\DoctrineUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;

final class DoctrineUserRepositoryTest extends TestCase
{
    private EntityManagerInterface|MockObject $entityManager;
    private EventDispatcherInterface|MockObject $eventDispatcher;
    private DoctrineUserRepository $userRepository;
    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = new DoctrineUserRepository(
            $this->entityManager = $this->createMock(
                EntityManagerInterface::class
            ),
            $this->eventDispatcher = $this->createMock(
                EventDispatcherInterface::class
            )
        );
    }

    public function test_handle_user_event_dispatching(): void
    {
        $userEmail = 'test@test.pl';
        $user = new User($userEmail);

        $this->entityManager->expects(self::once())
            ->method('persist');

        $this->eventDispatcher->expects(self::once())
            ->method('dispatch')
            ->with(self::isInstanceOf(UserCreatedEvent::class));

        $this->entityManager->expects(self::once())
            ->method('flush');

        $this->userRepository->save($user);
    }
}
