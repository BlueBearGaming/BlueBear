<?php

namespace Task;

use Exception;
use Mage\Task\BuiltIn\Symfony2\SymfonyAbstractTask;

class SymfonyInit extends SymfonyAbstractTask
{
    /**
     * Returns the Title of the Task
     *
     * @return string
     */
    public function getName()
    {
        return 'Symfony2 init...';
    }

    /**
     * Runs the task
     *
     * @return boolean
     * @throws Exception
     * @throws \Mage\Task\ErrorWithMessageException
     * @throws \Mage\Task\SkipException
     */
    public function run()
    {
        // shared directory
        $this->runSharedDirectory();

        $command = "setfacl -R -m u:www-data:rwX -m u:johnkrovitch:rwX app/cache app/logs";
        $this->runCommandRemote($command, $output);

        $command2 = "setfacl -dR -m u:www-data:rwx -m u:johnkrovitch:rwx app/cache app/logs";
        $this->runCommandRemote($command2, $output);

        return true;
    }

    /**
     * Creates "shared" directory, then creates "/shared/logs" and "/shared/app/config/parameters.yml" from .dist
     */
    protected function runSharedDirectory()
    {
        $projectDirectory = $this->getConfig()->deployment('to');
        // create shared directory
        $command = 'mkdir -p ' . $projectDirectory . '/shared';
        $this->runCommandRemote($command, $output);

        // create logs directory
        $command = 'mkdir -p ' . $projectDirectory . '/shared/logs';
        $this->runCommandRemote($command, $output);

        // app directory
        $command = 'mkdir -p ' . $projectDirectory . '/shared/app/config';
        $this->runCommandRemote($command, $output);

        // copy parameters.yml.dist
        $releasesDirectory = $this->getConfig()->release('directory', 'releases');
        $releaseId = $this->getConfig()->getReleaseId();
        $releasePath = $projectDirectory . '/' . $releasesDirectory . '/' . $releaseId;

        $command = 'cp ' . $releasePath . '/app/config/parameters.yml.dist ' . $projectDirectory . '/shared/app/config/parameters.yml';
        $command .= ' --backup -n';
        $this->runCommandRemote($command, $output);
    }
}