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
    protected function configure(): void
    {
        $this
            ->addArgument('NameBundle', InputArgument::REQUIRED, 'Argument description',null)
            ->addArgument('Path', InputArgument::OPTIONAL, 'Argument description', '')
//            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $nameBundle = $input->getArgument('NameBundle');
        $path = $input->getArgument('Path');
        /**
         * @desc
         */
        try {
            if($path)
                $dir = Path::normalize( $this->getProjectDir().'/src/'.$path.'/'. $nameBundle. $this::$suffix);
            else
                $dir = Path::normalize( $this->getProjectDir().'/src/'. $nameBundle. $this::$suffix);

            /**
             * Check exist file dir
             */
            if($this->filesystem->exists($dir)){
                $io->error($nameBundle. $this::$suffix.' '.'folder is exists in my directory src');
                return Command::FAILURE;
            }

            /**
             * Handle step by step
             */
            $folder = $nameBundle . $this::$suffix;
            $this::$nameSpace = $path;
            $this::$nameBundle = $nameBundle;

            $this->filesystem->mkdir($dir);
            $this->filesystem->dumpFile($dir.'/'.$folder.'.php', $this->generateContentBundle());
            $this->filesystem->mkdir($dir.'/Resources/config/');
            $this->filesystem->mkdir($dir.'/Resources/views/');
            $this->filesystem->mkdir($dir.'/Controller/');
            $this->filesystem->mkdir($dir.'/Entity/');
            $this->filesystem->dumpFile($dir.'/Entity/'.$nameBundle.'.php', $this->generateContentEntity());
            $this->filesystem->mkdir($dir.'/Repository/');
            $this->filesystem->dumpFile($dir.'/Repository/'.$nameBundle.'Repository'.'.php', $this->generateContentRepository());
            $this->filesystem->mkdir($dir.'/Form/');
            $this->filesystem->mkdir($dir.'/Extension/');

            /**
             * Automatic register bundle
             */
            $this->registerBundle($path, $nameBundle. $this::$suffix);
        } catch (IOExceptionInterface $exception) {
            echo "An error occurred while creating your directory at ".$exception->getPath();
            return Command::FAILURE;
        }
        $io->note(sprintf('You passed an argument: %s', $nameBundle));

        $io->success('We have create a bundle in dir: '.$dir);
        return Command::SUCCESS;
    }

    private function createBundle(string $dir,string $nameBundle): void
    {

    }

}
