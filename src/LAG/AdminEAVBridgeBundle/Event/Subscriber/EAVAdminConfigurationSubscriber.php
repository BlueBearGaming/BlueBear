<?php

namespace LAG\AdminEAVBridgeBundle\Event\Subscriber;

use LAG\AdminBundle\Event\AdminEvent;
use LAG\AdminBundle\Event\AdminEvents;
use LAG\AdminBundle\Event\ConfigurationEvent;
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
            AdminEvents::ADMIN_CONFIGURATION => 'adminCreate'
        ];
    }

    /**
     * @param ConfigurationEvent $event
     */
    public function adminCreate(ConfigurationEvent $event)
    {
        if (!array_key_exists($event->getAdminName(), $this->mapping)) {
            return;
        }
        // add mapping between a class, an admin and an eav family for the data provider
        $class = $event->getEntityClass();

        $this
            ->mapper
            ->addMapping(
                $class,
                $event->getAdminName(),
                $this->mapping[$event->getAdminName()]
            );
    }
}
