<?php

namespace Task;

require_once(__DIR__ . '/../../vendor/autoload.php');

use Exception;
use Mage\Console;
use Mage\Task\BuiltIn\Symfony2\SymfonyAbstractTask;
use Mage\Task\SkipException;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;

class SymfonyInit extends SymfonyAbstractTask
{
    use MageTaskTrait;

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
        // shared directories and files
        $this->setSharedFiles();
        // create permissions
        $this->setPermissions();
        // create parameters.yml if required and prompt new parameters from .dist
        $this->setParametersYml();
    }

    /**
     * Set permissions for cache and logs directory
     *
     * @throws Exception
     */
    public function setPermissions()
    {
        $deploymentUser = $this->getConfig()->getEnvironmentOption('user');
        $permissionsMethods = $this->getConfig()->getEnvironmentOption('permissions');

        if ($permissionsMethods == 'acl') {
            // acl require a deployment user
            if (!$deploymentUser) {
                throw new Exception('Invalid deployment user "' . $deploymentUser . "");
            }
            // run setfacl commands
            $command = "setfacl -R -m u:www-data:rwX -m u:{$deploymentUser}:rwX app/cache app/logs";
            $this->runCommandRemote($command, $output);

            $command = "setfacl -dR -m u:www-data:rwx -m u:{$deploymentUser}:rwx app/cache app/logs";
            $this->runCommandRemote($command, $output);
        } else if ($permissionsMethods) {
            // only support acl for now
            throw new Exception('Permissions methods not supported : "' . $permissionsMethods . '" (only "acl" option is supported');
        }
    }

    /**
     * Creates "shared" directory, then creates "/shared/logs" and "/shared/app/config/parameters.yml" from .dist
     */
    protected function setSharedFiles()
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
            $folderArray = explode('/', $folder);
            array_pop($folderArray);
            $command = 'mkdir -p ' . $sharedFolderName . '/' . $folder;
            $this->runCommandRemote($command);

            $command = "ln -nfs $sharedFolderName/$folder $currentCopy/" . implode('/', $folderArray);
            $this->runCommandRemote($command);
        }
        foreach ($linkedFiles as $folder) {
            $command = "ln -nfs $sharedFolderName/$folder $currentCopy/$folder";
            $this->runCommandRemote($command);
        }
    }

    public function setParametersYml()
    {
        // create config directory if not exists
        $this->mkDir($this->getSharedFolder() . '/app/config');

        // copy current release parameters.yml.dist into /shared/app/config/parameters.yml
        $parametersDist = $this->getCurrentReleaseDirectory() . 'app/config/parameters.yml.dist';
        $sharedParameters = $this->getSharedFolder() . '/app/config/parameters.yml';
        // copy without overwrite
        $this->copy($parametersDist, $sharedParameters);

        // match differences between two files
        $yamlParser = new Parser();
        // yml from .dist file
        $distYml = $yamlParser->parse($this->getFileContent($parametersDist));
        // yml from actual shared parameters.yml
        $parametersYml = $yamlParser->parse($this->getFileContent($sharedParameters));

        var_dump($distYml);
        var_dump($parametersYml);

        if (is_array($distYml) and is_array($parametersYml)) {
            echo 'lol';
            // searching for new key in parameters.yml.dist
            $differential = array_diff($distYml['parameters'], $parametersYml['parameters']);
            var_dump($differential);

            if (count($differential)) {
                Console::output('New parameters from parameters.dist.yml have been found !');

                foreach ($differential as $key => $defaultValue) {
                    Console::output("Enter value for <white>{$key}</white> (default: \"{$defaultValue}\"");
                    $inputValue = Console::readInput();

                    if ($inputValue) {
                        $parametersYml['parameters'][$key] = $inputValue;
                    } else {
                        $parametersYml['parameters'][$key] = $defaultValue;
                    }
                }
                Console::output('Dumping new parameters.yml');
                // dump new parameters values
                $dumper = new Dumper();
                $newParametersYml = $dumper->dump($parametersYml);
                // dump content into shared parameters.yml file
                $command = "echo \"{$newParametersYml}\" >> {$sharedParameters}";
                $this->runCommandRemote($command);
            }
        }
    }
}