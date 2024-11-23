<?php

declare(strict_types=1);

namespace App\Core\User\UserInterface\Cli;

use App\Common\Bus\QueryBusInterface;
use App\Core\User\Application\DTO\UserDTO;
use App\Core\User\Application\Query\GetInactiveUsersEmails\GetInactiveUsersEmailsQuery;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:user:get-inactive-users-emails',
    description: 'Pobieranie adresów email niaktywnych użytkowników'
)]
final class GetInactiveUsersEmails extends Command
{
    public function __construct(private readonly QueryBusInterface $bus)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inactiveUsers = $this->bus->dispatch(new (GetInactiveUsersEmailsQuery::class));

        /** @var UserDTO $user */
        foreach ($inactiveUsers as $user) {
            $output->writeln($user->email);
        }

        return Command::SUCCESS;
    }
}
