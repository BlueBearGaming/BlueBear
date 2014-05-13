<?php


namespace BlueBear\CoreBundle\Entity\Editor;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Timestampable;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use Doctrine\ORM\Mapping as ORM;

/**
 * Image are used in the editor
 *
 * @ORM\Table(name="editor_image")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Editor\ImageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Image
{
    use Id, Nameable, Timestampable;

    /**
     * Image file full path
     *
     * @ORM\Column(name="file_path", type="string", length=255)
     */
    protected $filePath;

    /**
     * Image file name
     *
     * @ORM\Column(name="file_name", type="string", length=255)
     */
    protected $fileName;

    /**
     * @ORM\JoinColumn(nullable=true)
     * @ORM\OneToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\Pencil")
     */
    protected $pencil;

    /**
     * @param mixed $filePath
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * @return mixed
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    public function getPencil()
    {
        return $this->pencil;
    }

    public function setPencil($pencil)
    {
        $this->pencil = $pencil;
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
} 