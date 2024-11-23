<?php

declare(strict_types=1);

namespace App\Tests\Unit\Common\EventManager;

use App\Common\EventManager\EventsCollectorDispatcher;
use App\Core\User\Domain\User;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

final class EventsCollectorDispatcherTest extends TestCase
{
    private EventDispatcherInterface|MockObject $eventDispatcher;
    private EventsCollectorDispatcher $eventsCollectorDispatcher;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->eventsCollectorDispatcher = new EventsCollectorDispatcher(
            $this->eventDispatcher = $this->createMock(
                EventDispatcher::class
            )
        );
    }

    public function test_handle_event_dispatching(): void
    {
        $userEmail = 'test@test.pl';
        $user = new User($userEmail);

        $this->eventDispatcher->expects(self::once())
            ->method('dispatch');

        $this->eventsCollectorDispatcher->dispatchEntityEvents($user);
    }
}
