<?php

namespace LAG\AdminEAVBridgeBundle\DataProvider;

use Exception;
use LAG\AdminBundle\DataProvider\DataProvider;
use LAG\AdminBundle\DataProvider\DataProviderInterface;
use LAG\AdminBundle\Repository\RepositoryInterface;
use LAG\AdminEAVBridgeBundle\Mapping\AdminEAVFamilyMapper;
use Sidus\EAVModelBundle\Configuration\FamilyConfigurationHandler;

/**
 * Override DataProvider to allow EAV entities creation with Family in constructor's argument.
 */
class EAVDataProvider extends DataProvider implements DataProviderInterface
{
    /**
     * @var AdminEAVFamilyMapper
     */
    protected $mapper;

    /**
     * @var FamilyConfigurationHandler
     */
    protected $handler;

    /**
     * EAVDataProvider constructor.
     *
     * @param RepositoryInterface $repository
     * @param AdminEAVFamilyMapper $mapper
     * @param FamilyConfigurationHandler $handler
     */
    public function __construct(
        RepositoryInterface $repository,
        AdminEAVFamilyMapper $mapper,
        FamilyConfigurationHandler $handler
    ) {
        parent::__construct($repository);

        $this->mapper = $mapper;
        $this->handler = $handler;
    }

    public function create()
    {
        $className = $this
            ->repository
            ->getClassName();
        $family = $this
            ->mapper
            ->getFamily($className);

        if (!$this->handler->hasFamily($family)) {
            throw new Exception($family.' not found in in family handler. Check your mapping configuration');
        }

        return new $className($this->handler->getFamily($family));
    }
}
