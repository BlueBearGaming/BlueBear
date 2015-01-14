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
    const IMAGE_TYPE_SINGLE_IMAGE = 'image.type.single_image';
    const IMAGE_TYPE_RPG_MAKER_TILESET = 'image.type.rpg_maker_tileset';
    const IMAGE_TYPE_RPG_MAKER_SPRITE = 'image.type.rpg_maker_sprite';
    const IMAGE_TYPE_AUTO = 'image.type.auto';

    use Id, Nameable, Timestampable;

    /**
     * @ORM\OneToOne(targetEntity="BlueBear\CoreBundle\Entity\Editor\Resource", cascade={"persist"}, fetch="EAGER")
     */
    protected $resource;

    /**
     * @return Resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @param mixed $resource
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    public function toArray()
    {
        $r = $this->getResource();
        return [
            'name' => $this->getName(),
            'fileName' => $r->getFileName(),
            'filePath' => $r->getFilePath(),
            'extension' => $r->getExtension(),
        ];
    }
} 