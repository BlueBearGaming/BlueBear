<?php

namespace Task;

use Mage\Task\AbstractTask;
use Symfony\Component\Finder\Finder;

class SymfonyPermissions extends AbstractTask
{
    /**
     * Returns the Title of the Task
     *
     * @return string
     */
    public function getName()
    {
        return 'Fix symfony2 permissions';
    }

    /**
     * Runs the task
     *
     * @return boolean
     * @throws \Exception
     * @throws \Mage\Task\ErrorWithMessageException
     * @throws \Mage\Task\SkipException
     */
    public function run()
    {
        $output = '';
        // TODO handle other permissions types

        //$finder = new Finder();
        //$finder->directories()->in();
        //die($this->getConfig()->getParameter('deployment'));


        $command = "setfacl -R -m u:www-data:rwX -m u:johnkrovitch:rwX app/cache app/logs";
        $this->runCommandRemote($command, $output);
        var_dump($output);
        $command2 = "setfacl -dR -m u:www-data:rwx -m u:johnkrovitch:rwx app/cache app/logs";
        $this->runCommandRemote($command2, $output);
        var_dump($output);
    }
}