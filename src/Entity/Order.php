<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Attribute\Groups;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;

use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;


#[ApiFilter(DateFilter::class, properties: ['created_at'])]
#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    operations: [
        new GetCollection(security: "is_granted('ROLE_PATRON') or is_granted('ROLE_BARMAN')", securityMessage: 'You are not allowed to get orders'),
        new Post(security: "is_granted('ROLE_PATRON') or is_granted('ROLE_BARMAN') or is_granted('ROLE_SERVEUR')  or object == user", securityMessage: 'You are not allowed to get this order'),
        new Get(security: "is_granted('ROLE_PATRON') or is_granted('ROLE_BARMAN') or is_granted('ROLE_SERVEUR') or object == user", securityMessage: 'You are not allowed to get this order'),
        new Put(security: "is_granted('ROLE_PATRON') or is_granted('ROLE_BARMAN') or is_granted('ROLE_SERVEUR') or object == user", securityMessage: 'You are not allowed to edit this order'),
        new Delete(security: "is_granted('ROLE_PATRON') or object == user", securityMessage: 'You are not allowed to delete this order'),
        new Patch(),
    ],
)]
#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]

class Order
{
    #[Groups('read')]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['read', 'write'])]
    #[ORM\Column(nullable: true)]
    private ?int $table_number = null;

    #[Groups(['read', 'write'])]
    #[ORM\Column(length: 255, options: ["default" => "Pending"])]
    private ?string $orderStatus = null;

    #[Groups(['read', 'write'])]
    #[ORM\ManyToOne]
    private ?User $waiter_id = null;

    #[Groups(['read', 'write'])]
    #[ORM\ManyToOne]
    private ?User $barman_id = null;

    #[Groups('read')]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    /**
     * @var Collection<int, Drink>
     */
    #[Groups(['read', 'write'])]
    #[ORM\ManyToMany(targetEntity: Drink::class)]
    private Collection $order_drinks;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getTableNumber(): ?int
    {
        return $this->table_number;
    }

    public function setTableNumber(?int $table_number): static
    {
        $this->table_number = $table_number;

        return $this;
    }

    public function getOrderStatus(): ?string
    {
        return $this->orderStatus;
    }

    public function setOrderStatus(string $orderStatus): static
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }

    public function getWaiterId(): ?User
    {
        return $this->waiter_id;
    }

    public function setWaiterId(?User $waiter_id): static
    {
        $this->waiter_id = $waiter_id;

        return $this;
    }

    public function getBarmanId(): ?User
    {
        return $this->barman_id;
    }

    public function setBarmanId(?User $barman_id): static
    {
        $this->barman_id = $barman_id;

        return $this;
    }

    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->order_drinks = new ArrayCollection();
    }

    /**
     * @return Collection<int, Drink>
     */
    public function getOrderDrinks(): Collection
    {
        return $this->order_drinks;
    }

    public function addOrderDrink(Drink $orderDrink): static
    {
        if (!$this->order_drinks->contains($orderDrink)) {
            $this->order_drinks->add($orderDrink);
        }

        return $this;
    }

    public function removeOrderDrink(Drink $orderDrink): static
    {
        $this->order_drinks->removeElement($orderDrink);

        return $this;
    }
}
