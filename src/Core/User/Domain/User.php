<?php

namespace App\Core\User\Domain;

use App\Common\EventManager\EventsCollectorTrait;
use App\Core\User\Domain\Exception\UserEmailInvalidException;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{
    use EventsCollectorTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned"=true}, nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=300, nullable=false)
     */
    private string $email;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default": false})
     */
    private bool $isActive = false;

    public function __construct(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new UserEmailInvalidException('Niepoprawny adres email.');
        }

        $this->id = null;
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
