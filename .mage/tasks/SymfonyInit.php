<?php

namespace Task;

use Exception;
use Mage\Task\BuiltIn\Symfony2\SymfonyAbstractTask;
use Mage\Task\SkipException;

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
        $this->runSharedDirectoryCommand();

        $command = "setfacl -R -m u:www-data:rwX -m u:johnkrovitch:rwX app/cache app/logs";
        $this->runCommandRemote($command, $output);

        $command2 = "setfacl -dR -m u:www-data:rwx -m u:johnkrovitch:rwx app/cache app/logs";
        $this->runCommandRemote($command2, $output);

        return true;
    }

    /**
     * Creates "shared" directory, then creates "/shared/logs" and "/shared/app/config/parameters.yml" from .dist
     */
    protected function runSharedDirectoryCommand()
    {
        $linkedFiles = $this->getParameter('linked_files', []);
        $linkedFolders = $this->getParameter('linked_folders', []);

        if (sizeof($linkedFiles) == 0 && sizeof($linkedFolders) == 0) {
            throw new SkipException('No files and folders configured for sym-linking.');
        }
        // shared folder
        $sharedFolderName = $this->getParameter('shared', 'shared');
        $sharedFolderName = rtrim($this->getConfig()->deployment('to'), '/') . '/' . $sharedFolderName;

        // create it if not exists
        $command = 'mkdir -p ' . $sharedFolderName;
        $this->runCommandRemote($command);

        // releases directory
        $releasesDirectory = $this->getConfig()->release('directory', 'releases');
        $releasesDirectory = rtrim($this->getConfig()->deployment('to'), '/') . '/' . $releasesDirectory;

        // copy parameters.yml.dist into parameters.yml
        $command = 'mkdir ' . $sharedFolderName . '/app/config -p';
        $this->runCommandRemote($command);

        $command = 'cp ' . $releasesDirectory . '/' . $this->getConfig()->getReleaseId()
            . '/app/config/parameters.yml.dist ' . $sharedFolderName . '/app/config/parameters.yml';
        $command .= ' -n';
        $this->runCommandRemote($command, $output);

        // shared folders and files
        $currentCopy = $releasesDirectory . '/' . $this->getConfig()->getReleaseId();

        foreach ($linkedFolders as $folder) {
            $command = "ln -nfs $sharedFolderName/$folder $currentCopy/";
            $this->runCommandRemote($command);
        }
        foreach ($linkedFiles as $folder) {
            $command = "ln -nfs $sharedFolderName/$folder $currentCopy/$folder";
            $this->runCommandRemote($command);
        }

    }
}