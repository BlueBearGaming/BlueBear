<?php

namespace BlueBear\CoreBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * ResourceSymlinkCommand
 *
 * Create a symbolic link from resources directory to web directory
 */
class ResourceSymlinkCommand extends Command
{
    public function configure()
    {
        $this
            ->setName('bluebear:resource')
            ->addArgument('test', InputArgument::REQUIRED, 'Available options : ')
            ->setHelp('Execute various task on resource (symlink creation...)');
    }

    public function run(InputInterface $input, OutputInterface $output)
    {
        $applicationPath = __DIR__ . '/../../../../';
        $destinationPath = realpath($applicationPath . 'web') . '/resources';
        $source = realpath($applicationPath . 'resources');
        $fileSystem = new Filesystem();
        $fileSystem->symlink($source, $destinationPath);

        $output->writeln('<info>Symbolic link was created</info>');
    }
} 