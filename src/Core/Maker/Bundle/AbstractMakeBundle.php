<?php

namespace App\Core\Maker\Bundle;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class AbstractMakeBundle extends Command implements MakeBundleInterface
{
    protected Filesystem $filesystem;
    const PREFIX_TABLE = 'amz';
    public static string $suffix = 'Bundle';
    public static ?string $nameSpace;
    public static ?string $nameBundle;
    public function __construct(
        Filesystem $filesystem,
        protected readonly KernelInterface $kernel,
    )
    {
        $this->filesystem = $filesystem;
        parent::__construct('make:bundle');
    }

    protected function getProjectDir(): string
    {
        return $this->kernel->getProjectDir();
    }

    public function registerBundle(?string $path,string $nameBundle): void
    {
        $file = $this->getProjectDir().'/config/bundles.php';
        $path = $path ? $path.'\\' : '';
        $newNamespace = 'App\\'. $path . $nameBundle.'\\'.$nameBundle . '::class => ["all" => true]';
        $currentContent = file_get_contents($file);

        $newContent = str_replace(
            'return [',
            "return [\n\t$newNamespace,",
            $currentContent
        );

        // write content new
        $this->filesystem->dumpFile($file, $newContent);
    }

    public function generateContentRepository(): string
    {
        $nameBundle = static::$nameBundle;
        $nameSpace = static::$nameSpace;
        if(empty($nameSpace) && empty($nameBundle)){
            return '';
        }
        $nameSpace = $nameSpace ? $nameSpace.'\\' : '';
        return '<?php
namespace App\\' . $nameSpace. $nameBundle. $this::$suffix .'\\Repository'. ';

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
///**
// * @method '. $nameBundle.'|null find($id, $lockMode = null, $lockVersion = null)
// * @method '. $nameBundle.'|null findOneBy(array $criteria, array $orderBy = null)
// * @method '. $nameBundle.'[]    findAll()
// * @method '. $nameBundle.'[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
// */
class ' . $nameBundle .'Repository' .' extends ServiceEntityRepository
{
//     public function __construct(ManagerRegistry $registry)
//    {
//        parent::__construct($registry, '. $nameBundle.'::class);
//    }
}
';
    }

    public function generateContentController()
    {
        $nameBundle = static::$nameBundle;
        $nameSpace = static::$nameSpace;
        if(empty($nameSpace) && empty($nameBundle)){
            return '';
        }
    }

    public  function generateContentBundle(): string
    {
        $nameBundle = static::$nameBundle;
        $nameSpace = static::$nameSpace;
        if(empty($nameSpace) && empty($nameBundle)){
            return '';
        }
        return '<?php
namespace App\\' .$nameSpace.'\\'. $nameBundle. $this::$suffix .';

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ' . $nameBundle. $this::$suffix . ' extends Bundle
{

}
';
    }

    public function generateContentEntity(): string
    {
        $nameBundle = static::$nameBundle;
        $nameSpace = static::$nameSpace;
        if(empty($nameSpace) && empty($nameBundle)){
            return '';
        }
        //* @ORM\Entity(repositoryClass='.$useRepo.')
        //* @ORM\Table(name='.$tableName.')
        $useRepo = '"App\\'.$nameSpace.'\\'. $nameBundle. $this::$suffix .'\\Repository\\'.$nameBundle.'Repository'.'"';
        $tableName = '"'.self::PREFIX_TABLE.'_'. strtolower($nameBundle) .'"';
        return '<?php
namespace App\\' .$nameSpace.'\\'. $nameBundle. $this::$suffix .'\\Entity'.';
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity()
 */
class ' . $nameBundle. '
{

}
';
    }
}