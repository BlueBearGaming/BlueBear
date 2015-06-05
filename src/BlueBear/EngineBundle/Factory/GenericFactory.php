<?php

namespace BlueBear\EngineBundle\Factory;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use Exception;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Yaml\Parser;

class GenericFactory
{
    use ContainerTrait;

    public function import()
    {
        $parse = new Parser();
        $data = $parse->parse(file_get_contents(__DIR__ . '/../Resources/data/race.yml'));

        if (!array_key_exists('bluebear.roguebear.race', $data)) {
            return false;
        }
        $racesConfiguration = $data['bluebear.roguebear.race'];

        if (!is_array($racesConfiguration)) {
            throw new Exception('bluebear.roguebear.race configuration should be an array');
        }
        $races = [];
        $racesData = $racesConfiguration['data'];
        $accessor = PropertyAccess::createPropertyAccessor();
        $class = $racesConfiguration['class'];

        foreach ($racesData as $raceName => $raceData) {
            $excludes = ['attributeModifiers'];
            $object = new $class;
            $accessor->setValue($object, 'code', $raceName);

            foreach ($raceData as $attributeName => $attributeData) {

                if (!in_array($attributeName, $excludes)) {
                    if (is_array($attributeData)) {
                        $accessor->setValue($object, $attributeName, $attributeData);
                    } else if (is_string($attributeData)) {
                        $accessor->setValue($object, $attributeName, $attributeData);
                    } else {
                        throw new Exception('Not handled parse type : ' . print_r($attributeData));
                    }
                }

            }
            $races[] = $object;


            var_dump($races);
            die;
        }




        return true;
    }

}
