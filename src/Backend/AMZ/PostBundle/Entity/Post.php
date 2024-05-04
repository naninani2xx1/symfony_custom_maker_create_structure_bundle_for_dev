<?php

namespace App\Backend\AMZ\PostBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity()
 * @ORM\Entity(repositoryClass=App\Backend\AMZ\PostBundle\Repository\PostRepository")
 * @ORM\Table(name="amz_post")
 */
class Post
{

}
