<?php

namespace Task;

use Exception;
use Mage\Task\BuiltIn\Composer\ComposerAbstractTask;
use Mage\Task\ErrorWithMessageException;
use Mage\Task\SkipException;

class ComposerInstall extends ComposerAbstractTask
{
    /**
     * Returns the Title of the Task
     * @return string
     */
    public function getName()
    {
        return 'Install composer and dependencies';
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
        $environment = $this->getParameter('env');

        if ($environment == 'prod') {
            $options = $options = ' --no-ansi --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader';
        } else {
            $options = 'dev';
        }
        $command = $this->getComposerCmd() . ' install' . $options;

        return $this->runCommand($command);
    }
}