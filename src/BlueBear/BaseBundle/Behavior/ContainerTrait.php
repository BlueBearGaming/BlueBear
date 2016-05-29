<?php

namespace BlueBear\BaseBundle\Behavior;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * ContainerTrait
 *
 * Capacity to have a ContainerInterface
 */
trait ContainerTrait
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Gets a service by id.
     *
     * @param string $id The service id
     * @return object The service
     */
    public function get($id)
    {
        return $this->container->get($id);
    }
}
