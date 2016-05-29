<?php

namespace BlueBear\BaseBundle\Entity\Behaviors;

use DateTime;

trait Timestampable
{
    /**
     * @var DateTime
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var DateTime
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @ORM\PrePersist()
     * @return $this
     */
    public function setCreatedAt()
    {
        if (!$this->createdAt) {
            $this->createdAt = new DateTime();
        }
    }

    /**
     * Created at cannot be set. But in some case (like imports...), it is required to set created at. Use this method
     * in this case
     *
     * @param DateTime $createdAt
     */
    public function forceCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     * @param null $value
     * @return $this
     */
    public function setUpdatedAt($value = null)
    {
        if ($value instanceof DateTime) {
            $this->updatedAt = $value;
        } else {
            $this->updatedAt = new DateTime();
        }
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
