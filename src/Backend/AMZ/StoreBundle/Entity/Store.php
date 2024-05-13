<?php
namespace App\Backend\AMZ\StoreBundle\Entity;
use Doctrine\DBAL\Types\Types;
use App\Backend\AMZ\StoreBundle\Repository\StoreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StoreRepository::class)]
#[ORM\Table(name: "amz_table_store")]
#[ORM\HasLifecycleCallbacks()]
class Store
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING,nullable: true)]
    private ?string $name = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
