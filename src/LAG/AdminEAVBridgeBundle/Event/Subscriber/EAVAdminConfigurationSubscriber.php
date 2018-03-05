<?php

namespace LAG\AdminEAVBridgeBundle\Event\Subscriber;

use LAG\AdminBundle\Admin\Event\AdminCreateEvent;
use LAG\AdminBundle\Admin\Event\AdminEvents;
use LAG\AdminEAVBridgeBundle\Mapping\AdminEAVFamilyMapper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EAVAdminConfigurationSubscriber implements EventSubscriberInterface
{
    /**
     * Mapping between Admins and EAV Families.
     *
     * @var array
     */
    protected $mapping;

    /**
     * @var AdminEAVFamilyMapper
     */
    protected $mapper;

    /**
     * EAVAdminConfigurationSubscriber constructor.
     *
     * @param array $mapping
     * @param AdminEAVFamilyMapper $mapper
     */
    public function __construct(array $mapping = [], AdminEAVFamilyMapper $mapper)
    {
        $this->mapping = $mapping;
        $this->mapper = $mapper;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            AdminEvents::ADMIN_CREATE => 'adminCreate'
        ];
    }

    /**
     * @param AdminCreateEvent $event
     */
    public function adminCreate(AdminCreateEvent $event)
    {
        if (!array_key_exists($event->getAdminName(), $this->mapping)) {
            return;
        }
        // add mapping between a class, an admin and an eav family for the data provider
        $class = $event
            ->getAdminConfiguration()['entity'];

        $this
            ->mapper
            ->addMapping(
                $class,
                $event->getAdminName(),
                $this->mapping[$event->getAdminName()]
            );
    }
}
