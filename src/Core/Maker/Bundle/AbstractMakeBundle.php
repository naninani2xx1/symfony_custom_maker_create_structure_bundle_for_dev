<?php

namespace App\Core\Maker\Bundle;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class AbstractMakeBundle extends Command implements MakeBundleInterface
{
    protected Filesystem $filesystem;
    public const SUFFIX = 'Bundle';
    public static ?string $nameSpace;
    public static ?string $nameBundle;


    public function __construct(
        Filesystem                         $filesystem,
        protected readonly KernelInterface $kernel,
    )
    {
        $this->filesystem = $filesystem;
        parent::__construct('make:bundle');
    }

    protected function getPrefixNameTable(): string
    {
        return 'amz';
    }

    protected function getProjectDir(): string
    {
        return $this->kernel->getProjectDir();
    }

    public function registerBundle(?string $path, string $nameBundle): void
    {
        $file = $this->getProjectDir() . '/config/bundles.php';
        $path = $path ? $path . '\\' : '';
        $newNamespace = 'App\\' . $path . $nameBundle . '\\' . $nameBundle . '::class => ["all" => true]';
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
        if (empty($nameSpace) && empty($nameBundle)) {
            return '';
        }

        return '<?php
namespace ' . $this->getNameSpaceRepository(). ';

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use ' . $this->getNameSpaceEntity() . '\\' . $nameBundle . ';
/**
 * @method ' . $nameBundle . '|null find($id, $lockMode = null, $lockVersion = null)
 * @method ' . $nameBundle . '|null findOneBy(array $criteria, array $orderBy = null)
 * @method ' . $nameBundle . '[]    findAll()
 * @method ' . $nameBundle . '[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ' . $nameBundle . 'Repository' . ' extends ServiceEntityRepository
{
     public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ' . $nameBundle . '::class);
    }
}
';
    }

    public function generateContentController(): string
    {
        $nameBundle = static::$nameBundle;
        $nameSpace = static::$nameSpace;
        if (empty($nameSpace) && empty($nameBundle)) {
            return '';
        }
        return '<?php
namespace '.$this->getNameSpaceController().';

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class '.$nameBundle.'Controller'.' {


}
        ';
    }

    public function generateContentExtension()
    {
        $nameBundle = static::$nameBundle;
        $nameSpace = static::$nameSpace;
        if (empty($nameSpace) && empty($nameBundle)) {
            return '';
        }
    }

    public function generateContentBundle(): string
    {
        $nameBundle = static::$nameBundle;
        $nameSpace = static::$nameSpace;
        if (empty($nameSpace) && empty($nameBundle)) {
            return '';
        }

        $nameSpace = $nameSpace ? 'App\\' . $nameSpace . $nameBundle . self::SUFFIX : 'App\\' . $nameBundle . self::SUFFIX;
        return '<?php
namespace ' . $nameSpace . ';

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ' . $nameBundle . self::SUFFIX . ' extends Bundle
{

}
';
    }

    public function generateContentEntity(): string
    {
        $nameBundle = static::$nameBundle;
        $nameSpace = static::$nameSpace;
        if (empty($nameSpace) && empty($nameBundle)) {
            return '';
        }

        $useRepo = $this->getNameSpaceRepository(). '\\' . $nameBundle . 'Repository' . '"';
        $tableName = '"' . $this->getPrefixNameTable() . '_' . strtolower($nameBundle) . '"';
        return '<?php
namespace ' . $this->getNameSpaceEntity(). ';
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity()
   * @ORM\Entity(repositoryClass='.$useRepo.')
   * @ORM\Table(name='.$tableName.')
 */
class ' . $nameBundle . '
{

}
';
    }

    private function getNameSpaceEntity(): string
    {
        $nameBundle = static::$nameBundle;
        $nameSpace = static::$nameSpace;
        return $nameSpace ? 'App\\' . $nameSpace . $nameBundle . self::SUFFIX . '\\Entity' : 'App\\' . $nameBundle . self::SUFFIX . '\\Entity';
    }

    private function getNameSpaceRepository(): string
    {
        $nameBundle = static::$nameBundle;
        $nameSpace = static::$nameSpace;
        return $nameSpace ? 'App\\' . $nameSpace . $nameBundle . self::SUFFIX . '\\Repository' : 'App\\' . $nameBundle . self::SUFFIX . '\\Repository';
    }

    private function getNameSpaceController(): string
    {
        $nameBundle = static::$nameBundle;
        $nameSpace = static::$nameSpace;
        return $nameSpace ? 'App\\' . $nameSpace . $nameBundle . self::SUFFIX . '\\Controller' : 'App\\' . $nameBundle . self::SUFFIX . '\\Controller';
    }
}