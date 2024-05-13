<?php
namespace App\Backend\AMZ\AccountBundle\Entity;
use Doctrine\DBAL\Types\Types;
use App\Backend\AMZ\AccountBundle\Repository\AccountRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[ORM\Table(name: "amz_table_account")]
#[ORM\HasLifecycleCallbacks()]
class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
}
