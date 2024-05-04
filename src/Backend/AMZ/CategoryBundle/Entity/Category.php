<?php
namespace App\Backend\AMZ\CategoryBundle\Entity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity()
   * @ORM\Entity(repositoryClass=App\Backend\AMZ\CategoryBundle\Repository\CategoryRepository")
   * @ORM\Table(name="amz_category")
 */
class Category
{

}
