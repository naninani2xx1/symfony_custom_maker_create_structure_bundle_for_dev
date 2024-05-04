<?php

namespace App\Command;

use App\Core\Maker\Bundle\AbstractMakeBundle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Path;


#[AsCommand(
    name: 'make:bundle',
    description: 'Create a structure the bundle in symfony',
)]
class BundleCommand extends AbstractMakeBundle
{
    const ROOT_FOLDER = 'src';
    protected function configure(): void
    {
        $this
            ->addArgument('NameBundle', InputArgument::REQUIRED, 'Argument description',null)
            ->addOption('path', '-p',InputArgument::OPTIONAL, 'Argument description', '');
        ;
    }

    protected function getPrefixNameTable(): string
    {
        return 'amz_table';
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $nameBundle = $input->getArgument('NameBundle');
        $path = $input->getOption('path');
        $io = new SymfonyStyle($input, $output);
        echo $path;
        if($path){
            if(str_starts_with($path, '\\') or !str_ends_with($path,'\\')){
                $io->error($path. ' not must start with and end with "\\');
                return Command::FAILURE;
            }
        }

        /**
         * @desc
         */
        try {
            if($path)
                $dir = Path::normalize( $this->getProjectDir().'/'.self::ROOT_FOLDER.'/'.$path. $nameBundle.  AbstractMakeBundle::SUFFIX);
            else
                $dir = Path::normalize( $this->getProjectDir().'/'.self::ROOT_FOLDER.'/'. $nameBundle.  AbstractMakeBundle::SUFFIX);
            /**
             * Check exist file dir
             */
            if($this->filesystem->exists($dir)){
                $io->error($nameBundle.  AbstractMakeBundle::SUFFIX.' '.'folder is exists in my directory src');
                return Command::FAILURE;
            }

            /**
             * Handle step by step
             */
            $folder = $nameBundle . AbstractMakeBundle::SUFFIX;
            AbstractMakeBundle::$nameSpace = $path;
            AbstractMakeBundle::$nameBundle = $nameBundle;

            $this->filesystem->mkdir($dir);
            $this->filesystem->dumpFile($dir.'/'.$folder.'.php', $this->generateContentBundle());
            $this->filesystem->mkdir($dir.'/Resources/config/');
            $this->filesystem->mkdir($dir.'/Resources/views/');
            $this->filesystem->mkdir($dir.'/Controller/');
            $this->filesystem->dumpFile($dir.'/Controller/'.$nameBundle.'Controller'.'.php', $this->generateContentController());
            $this->filesystem->mkdir($dir.'/Entity/');
            $this->filesystem->dumpFile($dir.'/Entity/'.$nameBundle.'.php', $this->generateContentEntity());
            $this->filesystem->mkdir($dir.'/Repository/');
            $this->filesystem->dumpFile($dir.'/Repository/'.$nameBundle.'Repository'.'.php', $this->generateContentRepository());
            $this->filesystem->mkdir($dir.'/Form/');
            $this->filesystem->mkdir($dir.'/Extension/');
            $this->filesystem->dumpFile($dir.'/Extension/'.$nameBundle.'Extension'.'.php', $this->generateContentExtension());


//            /**
//             * Automatic register bundle
//             */
//            $this->registerBundle($path, $nameBundle. AbstractMakeBundle::SUFFIX);
        } catch (IOExceptionInterface $exception) {
            echo "An error occurred while creating your directory at ".$exception->getPath();
            return Command::FAILURE;
        }
        $io->note(sprintf('You passed an argument: %s', $nameBundle));

        $io->success('We have create a bundle in dir: '.$dir);
        return Command::SUCCESS;
    }
}
