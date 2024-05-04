<?php
namespace App\Backend\AMZ\ProductBundle\Entity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity()
   * @ORM\Entity(repositoryClass=App\Backend\AMZ\ProductBundle\Repository\ProductRepository")
   * @ORM\Table(name="amz_product")
 */
class ProductEntity
{

}
