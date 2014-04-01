<?php


namespace BlueBear\CoreBundle\Entity\Editor;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Item
 *
 * @ORM\Table(name="editor_item")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Editor\ItemRepository")
 */
class Item
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Item name
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * Displayed object name
     *
     * @ORM\Column(type="string", name="display_name", length=255, nullable=true)
     */
    protected $displayName;

    /**
     * Object description
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\OneToMany(targetEntity="BlueBear\CoreBundle\Entity\Editor\Image", mappedBy="editorItem");
     */
    protected $images;

    /**
     * Item map layer
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $layer;

    /**
     * Item type (unit, land...)
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $type;

    /**
     * Tags
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $tags;

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * @return mixed
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param mixed $layer
     */
    public function setLayer($layer)
    {
        $this->layer = $layer;
    }

    /**
     * @return mixed
     */
    public function getLayer()
    {
        return $this->layer;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param mixed $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    public function addImage(Image $image)
    {
        if (!$this->images) {
            $this->images = new ArrayCollection();
        }
        $this->images->add($image);
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }
} 