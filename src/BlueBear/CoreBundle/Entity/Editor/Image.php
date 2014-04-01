<?php


namespace BlueBear\CoreBundle\Entity\Editor;

use Doctrine\ORM\Mapping as ORM;

/**
 * Image
 *
 * Image are used in the editor
 * @ORM\Table(name="editor_image")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Editor\ImageRepository")
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Image file path
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $filePath;

    /**
     * Image name
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * Editor items which image belongs
     *
     * @ORM\ManyToOne(targetEntity="BlueBear\CoreBundle\Entity\Editor\Item", inversedBy="images");
     */
    protected $item;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

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

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param mixed $item
     */
    public function setItem($item)
    {
        $this->item = $item;
    }
} 