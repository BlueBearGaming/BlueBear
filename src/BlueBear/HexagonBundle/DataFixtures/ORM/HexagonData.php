<?php

namespace BlueBear\HexagonBundle\DataFixtures\ORM;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\CoreBundle\Entity\Editor\Image;
use BlueBear\CoreBundle\Entity\Map\Context;
use BlueBear\CoreBundle\Entity\Map\Layer;
use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Entity\Map\MapItem;
use BlueBear\CoreBundle\Entity\Map\Pencil;
use BlueBear\CoreBundle\Entity\Map\PencilSet;
use BlueBear\CoreBundle\Utils\Position;
use BlueBear\EngineBundle\Entity\EntityModel;
use BlueBear\EngineBundle\Manager\EntityModelManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Util\XmlUtils;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\File\File;
use UnexpectedValueException;

class HexagonData implements FixtureInterface, ContainerAwareInterface
{
    use ContainerTrait;

    /** @var EntityManager */
    protected $manager;

    /** @var Layer[] */
    protected $layers = [];

    /** @var EntityModel[] */
    protected $entityModels = [];

    /**
     * Get EntityModelManager instead of ObjectManager for entity model to use logic from Manager to create linked
     * behaviors and attributes
     *
     * @var EntityModelManager
     */
    protected $entityModelManager;

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->entityModelManager = $this->container->get('bluebear.manager.entity_model');
        $this->createMapItems();
    }

    protected function createMapItems()
    {
        $imageManager = $this->container->get('bluebear.manager.image');
        $applicationPath = $this->container->get('kernel')->getRootDir();

        $fs = new Filesystem();
        $fs->mkdir($applicationPath.'/../web/resources/images');

        $spritePath = $applicationPath.'/../fixtures/hexagontiles/Spritesheet/complete.png';
        /** @var Image $sprite */
        $sprite = $imageManager->addFile(new File($spritePath), 'hexagontile.png', 'image');
        $fs->copy(
            $spritePath,
            $applicationPath.'/../web/resources/images/complete.png',
            true
        );

        $pencilSet = new PencilSet();
        $pencilSet->setLabel('Hexagon pencil set');
        $pencilSet->setName('hexagon_pencil_set');
        $pencilSet->setType('hexagonal');
        $pencilSet->setSprite($sprite);
        $this->manager->persist($pencilSet);

        $spriteMapPath = $applicationPath.'/../fixtures/hexagontiles/Spritesheet/complete.xml';
        $spriteMap = XmlUtils::loadFile($spriteMapPath);
        $xpath = new \DOMXPath($spriteMap);

        /** @var \DOMElement $item */
        foreach ($xpath->query('/TextureAtlas/SubTexture') as $item) {
            $fileName = $item->getAttribute('name');
            $type = $this->findPencilType($fileName);
            if (null === $type) {
                break;
            }
            $pencil = new Pencil();
            $pencil->setName(str_replace('.png', '', $fileName));
            $pencil->setPencilSet($pencilSet);
            $pencil->setType($type);
            $pencil->setSpriteX($item->getAttribute('x'));
            $pencil->setSpriteY($item->getAttribute('y'));
            $pencil->setSpriteWidth($item->getAttribute('width'));
            $pencil->setSpriteHeight($item->getAttribute('height'));
            $this->manager->persist($pencil);
        }

        $this->manager->flush();
    }

    /**
     * @param $fileName
     *
     * @return string|null
     */
    protected function findPencilType($fileName)
    {
        $spriteMapping = [
            '/^alien.*/' => 'unit',
            '/^bush.*/' => 'props',
            '/^flower.*/' => 'props',
            '/^hill.*/' => 'props',
            '/^pine.*/' => 'props',
            '/^rock.*/' => 'props',
            '/^smallRock.*/' => 'props',
            '/^tile.*/' => 'land',
            '/^tree.*/' => 'props',
        ];

        foreach ($spriteMapping as $pattern => $type) {
            if (1 === preg_match($pattern, $fileName)) {
                return $type;
            }
        }

        return null;
    }
}
