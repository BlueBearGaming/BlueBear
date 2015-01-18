<?php

namespace BlueBear\CoreBundle\Entity\Editor;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Timestampable;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Upload
 *
 * @ORM\Table(name="resource")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Editor\ResourceRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"image" = "Image"})
 * @Serializer\ExclusionPolicy("all")
 */
class Resource
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
     */
    public function setOriginalFileName($originalFileName)
    {
        $this->originalFileName = $originalFileName;
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
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }
    
    public function __toString() {
        return (string) $this->getFileName();
    }
}
