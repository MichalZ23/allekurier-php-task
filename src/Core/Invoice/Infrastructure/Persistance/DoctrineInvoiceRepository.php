<?php

namespace App\Core\Invoice\Infrastructure\Persistance;

use App\Common\EventManager\EventsCollectorDispatcher;
use App\Core\Invoice\Domain\Invoice;
use App\Core\Invoice\Domain\Repository\InvoiceRepositoryInterface;
use App\Core\Invoice\Domain\Status\InvoiceStatus;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineInvoiceRepository implements InvoiceRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly EventsCollectorDispatcher $eventsCollectorDispatcher
    ) {}

    public function getInvoicesWithGreaterAmountAndStatus(int $amount, InvoiceStatus $invoiceStatus): array
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('i')
            ->from(Invoice::class, 'i')
            ->where('i.status = :invoice_status')
            ->andWhere('i.amount > :amount')
            ->setParameters([
                ':invoice_status' => $invoiceStatus,
                ':amount' => $amount,
            ])
            ->getQuery()
            ->getResult();
    }

    public function save(Invoice $invoice): void
    {
        $this->entityManager->persist($invoice);
        $this->eventsCollectorDispatcher->dispatchEntityEvents($invoice);
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}
