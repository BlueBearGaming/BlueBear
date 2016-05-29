<?php

namespace BlueBear\CoreBundle\Entity\Game\Save;

use BlueBear\CoreBundle\Entity\Behavior\Id;
use BlueBear\CoreBundle\Entity\Behavior\Nameable;
use BlueBear\CoreBundle\Entity\Behavior\Timestampable;
use BlueBear\CoreBundle\Entity\Map\Context;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="save")
 * @ORM\Entity(repositoryClass="BlueBear\CoreBundle\Entity\Game\Save\SaveRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Save
{
    use Id, Nameable, Timestampable;

    /**
     * @var Context
     * @ORM\ManyToOne(targetEntity="BlueBear\CoreBundle\Entity\Map\Context")
     */
    protected $context;

    /**
     * @return Context
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param Context $context
     */
    public function setContext(Context $context)
    {
        $this->context = $context;
    }
}
