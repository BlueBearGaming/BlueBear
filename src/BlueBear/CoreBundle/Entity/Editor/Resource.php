<?php

namespace BlueBear\CoreBundle\Entity\Editor;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Timestampable;
use BlueBear\CoreBundle\Manager\ResourceManager;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use SplFileInfo;

/**
 * Upload
 *
 * @ORM\Table(name="resource")
 * @ORM\Entity(repositoryClass="ResourceRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Resource
{
    use Id, Nameable, Label, Timestampable;
    
    public function __construct(SplFileInfo $file = null) {
        if ($file) {
            $ad = realpath(ResourceManager::getApplicationDirectory());
            $this->fileName = $file->getBasename();
            if (0 === strpos($file->getRealPath(), $ad)) {
                $this->filePath = dirname(str_replace($ad, '', $file->getRealPath()));
            } else {
                $this->filePath = dirname($file->getPath());
            }
        }
    }

    /**
     * @ORM\Column(name="file_path", type="string", length=255)
     */
    protected $filePath;

    /**
     * @ORM\Column(name="file_name", type="string", length=255)
     */
    protected $fileName;

    /**
     * @return mixed
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @param mixed $filePath
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Get full path to resource file
     *
     * @return string
     */
    public function getFullPath()
    {
        return ResourceManager::getApplicationDirectory() . '/' . trim($this->getFilePath(), '/') . '/' . $this->getFileName();
    }

    public function getExtension()
    {
        $array = explode('.', $this->fileName);

        return array_pop($array);
    }

    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param mixed $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * Return file content
     *
     * @return string
     * @throws Exception
     */
    public function getFileContent()
    {
        if (!$this->fileExists()) {
            return null;
        }
        return base64_encode(file_get_contents($this->getFullPath()));
    }
    
    public function __toString() {
        return (string) $this->getFileName();
    }
    
    public function fileExists() {
        return file_exists($this->getFullPath());
    }
} 