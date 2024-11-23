<?php

namespace App\Tests\Unit\Core\User\Infrastructure\Persistence;

use App\Common\EventManager\EventsCollectorDispatcher;
use App\Core\User\Domain\User;
use App\Core\User\Infrastructure\Persistance\DoctrineUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;

final class DoctrineUserRepositoryTest extends TestCase
{
    private EntityManagerInterface|MockObject $entityManager;
    private EventDispatcherInterface|MockObject $eventCollectorDispatcher;
    private DoctrineUserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = new DoctrineUserRepository(
            $this->entityManager = $this->createMock(
                EntityManagerInterface::class
            ),
            $this->eventCollectorDispatcher = $this->createMock(
                EventsCollectorDispatcher::class
            )
        );
    }

    public function test_handle_user_event_dispatching(): void
    {
        $userEmail = 'test@test.pl';
        $user = new User($userEmail);

        $this->entityManager->expects(self::once())
            ->method('persist');

        $this->eventCollectorDispatcher->expects(self::once())
            ->method('dispatchEntityEvents')
            ->with(self::isInstanceOf(User::class));

        $this->userRepository->save($user);
    }
}
