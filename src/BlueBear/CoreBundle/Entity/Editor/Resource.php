<?php

namespace BlueBear\CoreBundle\Entity\Editor;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Timestampable;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Uploaded resource
 *
 * @ORM\Table(name="resource")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Editor\ResourceRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"image" = "Image"})
 * @Serializer\ExclusionPolicy("all")
 */
class Resource implements \JsonSerializable
{
    use Id, Timestampable;

    /**
     * Generated real file name
     * @var string
     * @ORM\Column(name="file_name", type="string", length=255, unique=true)
     * @Serializer\Expose()
     */
    protected $fileName;

    /**
     * Original fileName from upload or import script
     * @var string
     * @ORM\Column(name="original_file_name", type="string", length=255)
     * @Serializer\Expose()
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
            'id' => $this->getId(),
            'fileName' => $this->getFileName(),
            'originalFileName' => $this->getOriginalFileName(),
        ];
    }

    public function getExtension()
    {
        $pos = strrpos($this->getOriginalFileName(), '.');
        if ($pos) {
            return substr($this->getOriginalFileName(), $pos + 1);
        }
    }

    public function getType() {
        return 'resource';
    }
}
