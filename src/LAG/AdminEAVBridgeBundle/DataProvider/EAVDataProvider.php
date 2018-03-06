<?php

namespace LAG\AdminEAVBridgeBundle\DataProvider;

use Exception;
use LAG\AdminBundle\DataProvider\DataProvider;
use LAG\AdminBundle\Repository\RepositoryInterface;
use LAG\AdminEAVBridgeBundle\Mapping\AdminEAVFamilyMapper;
use Sidus\EAVModelBundle\Registry\FamilyRegistry;

/**
 * Override DataProvider to allow EAV entities creation with Family in constructor's argument.
 */
class EAVDataProvider extends DataProvider
{
    /**
     * @var AdminEAVFamilyMapper
     */
    protected $mapper;

    /**
     * @var FamilyRegistry
     */
    protected $familyRegistry;

    /**
     * @param RepositoryInterface  $repository
     * @param AdminEAVFamilyMapper $mapper
     * @param FamilyRegistry       $familyRegistry
     */
    public function __construct(
        RepositoryInterface $repository,
        AdminEAVFamilyMapper $mapper,
        FamilyRegistry $familyRegistry
    ) {
        parent::__construct($repository);

        $this->mapper = $mapper;
        $this->familyRegistry = $familyRegistry;
    }

    /**
     * @return object|\Sidus\EAVModelBundle\Entity\DataInterface
     * @throws Exception
     */
    public function create()
    {
        $className = $this->repository->getClassName();
        $family = $this->mapper->getFamily($className);

        if (!$this->familyRegistry->hasFamily($family)) {
            throw new Exception($family.' not found in in family handler. Check your mapping configuration');
        }

        return $this->familyRegistry->getFamily($family)->createData();
    }
}
