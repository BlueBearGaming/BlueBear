<?php

namespace BlueBear\HexagonBundle\DataFixtures\ORM;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use App\Entity\Editor\Image;
use App\Entity\Map\Context;
use App\Entity\Map\Layer;
use App\Entity\Map\Map;
use App\Entity\Map\Pencil;
use App\Entity\Map\PencilSet;
use BlueBear\EngineBundle\Entity\EntityModel;
use BlueBear\EngineBundle\Manager\EntityModelManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Util\XmlUtils;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

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
        $pencilSet = $this->createPencilSet();
        $this->createMap($pencilSet);
    }

    /**
     * @param PencilSet $pencilSet
     */
    protected function createMap(PencilSet $pencilSet)
    {
        $layers = $this
            ->container
            ->get('bluebear.manager.layer')
            ->findAll();

        $map = new Map();
        $map->setLayers($layers);
        $map->addPencilSet($pencilSet);
        $map->setCellSize(65);
        $map->setName('hexagonal_map');
        $map->setLabel('Hexagonal Map');
        $map->setType('hexagonal');
        $this->manager->persist($map);

        $context = new Context();
        $context->setLabel('Initial context');
        $context->setMap($map);
        $this->manager->persist($context);

        $this->manager->flush();
    }

    /**
     * @return PencilSet
     */
    protected function createPencilSet()
    {
        $imageManager = $this->container->get('bluebear.manager.image');
        $applicationPath = $this->container->get('kernel')->getRootDir();

        $assetRepository = $applicationPath.'/../fixtures/hexagontiles/Spritesheet';

        $fs = new Filesystem();
        $fs->mkdir($applicationPath.'/../web/resources/images');

        $spritePath = $assetRepository.'/complete.png';
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

        $spriteMapPath = $assetRepository.'/complete.xml';
        $spriteMap = XmlUtils::loadFile($spriteMapPath);
        $xpath = new \DOMXPath($spriteMap);


        mt_srand(37);
        /** @var \DOMElement $item */
        foreach ($xpath->query('/TextureAtlas/SubTexture') as $item) {
            $this->buildPencil($pencilSet, $item);
        }

        $this->manager->flush();

        return $pencilSet;
    }

    /**
     * @param PencilSet   $pencilSet
     * @param \DOMElement $item
     */
    protected function buildPencil(PencilSet $pencilSet, \DOMElement $item)
    {
        $fileName = $item->getAttribute('name');
        $name = str_replace('.png', '', $fileName);
        $type = $this->findPencilType($name);
        if (null === $type) {
            return;
        }

        $pencil = new Pencil();
        $pencil->setName(strtolower($name));
        $pencil->setLabel($this->humanize($name));
        $pencil->setPencilSet($pencilSet);
        $pencil->setType($type);
        $pencil->setSpriteX($item->getAttribute('x'));
        $pencil->setSpriteY($item->getAttribute('y'));
        $pencil->setSpriteWidth($item->getAttribute('width'));
        $pencil->setSpriteHeight($item->getAttribute('height'));
        $this->setImageDimensions($pencil);

        $this->manager->persist($pencil);
    }

    /**
     * @param $fileName
     *
     * @return string|null
     */
    protected function findPencilType($fileName)
    {
        $spriteMapping = [
            // special cases
            '/^tile.*_tile$/' => null, // Do not match cell size

            '/^alien/' => 'units',
            '/^bush/' => 'props',
            '/^flower/' => 'props',
            '/^hill/' => null, // They are ugly
            '/^pine/' => 'props',
            '/^rock/' => 'props',
            '/^smallRock/' => 'props',
            '/^tile/' => 'land',
            '/^tree/' => 'props',
        ];

        foreach ($spriteMapping as $pattern => $type) {
            if (1 === preg_match($pattern, $fileName)) {
                return $type;
            }
        }

        return null;
    }

    /**
     * @param Pencil $pencil
     */
    protected function setImageDimensions(Pencil $pencil)
    {
        $pencil->setWidth($pencil->getSpriteWidth() / 65);
        $pencil->setHeight($pencil->getSpriteHeight() / 60);
        if ('land' === $pencil->getType()) {
            $pencil->setImageY(0.18);
        }

        if (1 === preg_match('/^(pine|tree)/', $pencil->getName())) {
            $pencil->setImageX(mt_rand(-40, 40) / 100);
            $pencil->setImageY(-0.6 + mt_rand(-40, 40) / 100);

            return;
        }

        if (1 === preg_match('/^(flower|smallrock|bush)/', $pencil->getName())) {
            $pencil->setImageX(mt_rand(-40, 40) / 100);
            $pencil->setImageY(mt_rand(-40, 40) / 100);

            return;
        }

        if (0 === strpos($pencil->getName(), 'rock')) {
            $pencil->setImageX(-0.02);
            $pencil->setImageY(-0.05);

            return;
        }

        if (0 === strpos($pencil->getName(), 'alien')) {
            $pencil->setImageY(-0.3);

            return;
        }
    }

    /**
     * @param string $text
     *
     * @return string
     */
    protected function humanize($text)
    {
        return ucfirst(strtolower(trim(preg_replace(['/([A-Z])/', '/[_\s]+/'], ['_$1', ' '], $text))));
    }
}
