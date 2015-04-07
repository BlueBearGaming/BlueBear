<?php

namespace Task;

use Exception;
use Mage\Task\BuiltIn\Symfony2\SymfonyAbstractTask;
use Mage\Task\ErrorWithMessageException;
use Mage\Task\SkipException;

class Doctrine extends SymfonyAbstractTask
{

    /**
     * Returns the Title of the Task
     * @return string
     */
    public function getName()
    {
        return 'Doctrine commands';
    }

    /**
     * Runs the task
     *
     * @return boolean
     * @throws Exception
     * @throws ErrorWithMessageException
     * @throws SkipException
     */
    public function run()
    {
        $environment = $this->getParameter('env', 'dev');

        $releasesDirectory = $this->getConfig()->release('directory', 'releases');
        $releasesDirectory = rtrim($this->getConfig()->deployment('to'), '/') . '/' . $releasesDirectory;
        $currentCopy = $releasesDirectory . '/' . $this->getConfig()->getReleaseId();
        $doctrineCommand = 'cd ' . $currentCopy . ' && app/console doctrine:';

        if ($this->getParameter('update')) {
            // TODO make a backup
            $command = $doctrineCommand . 'schema:update --force  --no-ansi --env=' . $environment;
            $this->runCommandRemote($command);
        }
        return true;
    }
}