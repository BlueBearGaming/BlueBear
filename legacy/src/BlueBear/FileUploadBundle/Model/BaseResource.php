<?php

namespace BlueBear\FileUploadBundle\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * Uploaded resource
 */
abstract class BaseResource implements \JsonSerializable
{
    /**
     * Generated real file name
     * @var string
     * @ORM\Column(name="file_name", type="string", length=255, unique=true)
     */
    protected $fileName;

    /**
     * Original fileName from upload or import script
     * @var string
     * @ORM\Column(name="original_file_name", type="string", length=255)
     */
    protected $originalFileName;

    /**
     * @return string
     */
    public function getOriginalFileName()
    {
        return $this->originalFileName;
    }

    /**
     * @param string $originalFileName
     * @return $this
     */
    public function setOriginalFileName($originalFileName)
    {
        $this->originalFileName = $originalFileName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     * @return $this
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

    public function __toString()
    {
        return (string) $this->getFileName();
    }

    /**
     * Serialize automatically the entity when passed to json_encode
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'fileName' => $this->getFileName(),
            'originalFileName' => $this->getOriginalFileName(),
        ];
    }
}
