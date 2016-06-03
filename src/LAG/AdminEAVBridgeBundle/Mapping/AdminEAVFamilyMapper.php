<?php

namespace LAG\AdminEAVBridgeBundle\Mapping;


use Exception;

class AdminEAVFamilyMapper
{
    /**
     * Admin names indexed by class
     *
     * @var array
     */
    protected $adminMapping = [];

    /**
     * EAV Families indexed by class
     *
     * @var array
     */
    protected $familyMapping = [];

    public function addMapping($class, $admin, $family)
    {
        $this->adminMapping[$class] = $admin;
        $this->familyMapping[$class] = $family;
    }

    public function getAdmin($class)
    {
        if (!array_key_exists($class, $this->adminMapping)) {
            throw new Exception($class . ' not found in Admin EAV bridge mapping. Check your configuration');
        }

        return $this->adminMapping[$class];
    }

    public function getFamily($class)
    {
        if (!array_key_exists($class, $this->familyMapping)) {
            throw new Exception($class . ' not found in Admin EAV bridge mapping. Check your configuration');
        }

        return $this->familyMapping[$class];
    }
}
