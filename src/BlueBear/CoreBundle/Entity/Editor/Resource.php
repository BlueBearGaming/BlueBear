<?php

namespace BlueBear\CoreBundle\Entity\Editor;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Label;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Timestampable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Upload
 *
 * @ORM\Table(name="resource")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Editor\ResourceRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Resource
{
    use Id, Nameable, Label, Timestampable;

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

    public function getFullPath()
    {
        return $this->getFilePath() . '/' . $this->getFileName();
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

    public function getFileContent()
    {
        if (strpos($this->getFilePath(), '../')) {
            throw new \Exception('Security error in file path for file_get_content');
        }
        return base64_encode(file_get_contents($this->getFullPath()));
    }
} 