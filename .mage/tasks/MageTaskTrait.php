<?php

namespace Task;

use Mage\Config;

trait MageTaskTrait
{
    public abstract function runCommandRemote($command, &$output = null, $cdToDirectoryFirst = true);
    public abstract function getParameter($name, $default = null);

    /**
     * @return Config
     */
    public abstract function getConfig();

    /**
     * Return shared folder path (eg "/var/www/my_project/shared/"
     *
     * @return mixed
     */
    public function getSharedFolder()
    {
        return $this->getParameter('shared', 'shared');
    }

    /**
     * Return current release directory path (eg "/var/www/my_project/releases/20140215235612/"). Ends with slash
     *
     * @return string
     */
    public function getCurrentReleaseDirectory()
    {
        // releases directory from config
        $releasesDirectory = $this->getConfig()->release('directory', 'releases');
        // trim and concat with deployment path
        $releasesDirectory = rtrim($this->getConfig()->deployment('to'), '/') . '/' . $releasesDirectory;
        // get current release name
        $releasesDirectory .= '/' . $this->getConfig()->getReleaseId() . '/';

        return $releasesDirectory;
    }

    /**
     * Create a remote directory
     *
     * @param string $path
     * @param bool $createParents
     */
    public function mkDir($path, $createParents = true)
    {
        // create config directory if not exists
        $command = "mkdir {$path}";

        if ($createParents) {
            $command .= ' -p';
        }
        // run mkdir remote command
        $this->runCommandRemote($command);
    }

    /**
     * Copy a remote file into a target
     *
     * @param $sourcePath
     * @param $targetPath
     * @param bool $overwrite
     */
    public function copy($sourcePath, $targetPath, $overwrite = false)
    {
        $command = "cp {$sourcePath} {$targetPath}";

        if (!$overwrite) {
            // no file overwrite
            $command .= ' -n';
        }
        $this->runCommandRemote($command);
    }

    /**
     * Return remote file content
     *
     * @param $path
     * @return mixed
     */
    public function getFileContent($path)
    {
        $command = "cat {$path}";
        // display file in output
        $this->runCommandRemote($command, $output);

        return $output;
    }
}