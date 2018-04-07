<?php

namespace LAG\AdminEAVBridgeBundle\DataProvider;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Exception;
use LAG\AdminBundle\Bridge\Doctrine\Orm\DataProvider\OrmDataProvider;
use LAG\AdminEAVBridgeBundle\Mapping\AdminEAVFamilyMapper;
use Sidus\EAVModelBundle\Registry\FamilyRegistry;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Override DataProvider to allow EAV entities creation with Family in constructor's argument.
 */
class EAVDataProvider extends OrmDataProvider
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
     * @param EntityRepository         $repository
     * @param AdminEAVFamilyMapper     $mapper
     * @param FamilyRegistry           $familyRegistry
     * @param EntityManagerInterface   $entityManager
     * @param EventDispatcherInterface $eventDispatcher
     * @param RequestStack             $requestStack
     */
    public function __construct(
        EntityRepository $repository,
        AdminEAVFamilyMapper $mapper,
        FamilyRegistry $familyRegistry,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        RequestStack $requestStack
    ) {
        parent::__construct($entityManager, $eventDispatcher, $requestStack);

        $this->mapper = $mapper;
        $this->familyRegistry = $familyRegistry;
    }

    /**
     * @return mixed|\Sidus\EAVModelBundle\Entity\DataInterface
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
