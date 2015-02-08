<?php

namespace BlueBear\FileUploadBundle\Entity;

use BlueBear\FileUploadBundle\Model\BaseResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * Upload
 *
 * @ORM\Table(name="bluebear_resource")
 * @ORM\Entity(repositoryClass="BlueBear\FileUploadBundle\Entity\ResourceRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"resource" = "BlueBear\FileUploadBundle\Entity\Resource"})
 */
class Resource extends BaseResource
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

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
     * Serialize automatically the entity when passed to json_encode
     * @return array
     */
    public function jsonSerialize()
    {
        $json = parent::jsonSerialize();
        $json['id'] = $this->getId();
        return $json;
    }

    public function getType()
    {
        return 'resource';
    }
}
