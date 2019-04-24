<?php

namespace BlueBear\GameChessBundle\DataFixtures\ORM;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use App\Entity\Editor\Image;
use App\Entity\Map\Context;
use App\Entity\Map\Layer;
use App\Entity\Map\Map;
use App\Entity\Map\MapItem;
use App\Entity\Map\Pencil;
use App\Entity\Map\PencilSet;
use App\Utils\Position;
use BlueBear\EngineBundle\Entity\EntityModel;
use BlueBear\EngineBundle\Manager\EntityModelManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\File\File;
use UnexpectedValueException;

class ChessGameData implements FixtureInterface, ContainerAwareInterface
{
    use ContainerTrait;

    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * Get EntityModelManager instead of ObjectManager for entity model to use logic from Manager to create linked
     * behaviors and attributes
     *
     * @var EntityModelManager
     */
    protected $entityModelManager;

    /**
     * @var Layer[]
     */
    protected $layers = [];

    /**
     * @var EntityModel[]
     */
    protected $entityModels = [];

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
        $pencilSet = new PencilSet();
        $pencilSet->setLabel('Chess pencil set');
        $pencilSet->setName('chess_pencil_set');
        $pencilSet->setType('square');
        $this->manager->persist($pencilSet);

        $fs = new Filesystem();
        $fs->mkdir($applicationPath.'/../web/resources/images');

        $finder = new Finder();
        $finder->files()->in($applicationPath.'/../fixtures/chess');
        $pieces = [];
        /** @var SplFileInfo $fileInfo */
        foreach ($finder as $fileInfo) {
            /** @var Image $image */
            $image = $imageManager->addFile(new File($fileInfo->getRealPath()), $fileInfo->getFilename(), 'image');
            $pencilType = strpos($fileInfo->getFilename(), 'bg') ? 'land' : 'units';
            $piecePencil = new Pencil();
            $piecePencil->setName($fileInfo->getFilename());
            $piecePencil->setLabel(ucfirst(str_replace('_', ' ', substr($fileInfo->getFilename(), 0, -4))));
            $piecePencil->setType($pencilType);
            $piecePencil->setImage($image);
            $piecePencil->setPencilSet($pencilSet);

            $this->manager->persist($piecePencil);
            $pieces[str_replace('.png', '', $fileInfo->getFilename())] = $piecePencil;
            $fs->copy($fileInfo->getRealPath(), $applicationPath.'/../web/resources/images/'.$fileInfo->getFilename(), true);
        }
        $layers = $this
            ->container
            ->get('bluebear.manager.layer')
            ->findAll();
        /** @var Layer $layer */
        foreach ($layers as $layer) {
            $this->layers[$layer->getType()] = $layer;
        }
        $rowIndex = 0;
        $context = new Context();
        $context->setLabel('Initial context');
        $isBlack = true;

        while ($rowIndex < 8) {
            $columnIndex = 0;
            $isBlack = !$isBlack;

            while ($columnIndex < 8) {
                $mapItemBackground = new MapItem();
                $mapItemBackground->setPencil($isBlack ? $pieces['black_bg'] : $pieces['white_bg']);
                $mapItemBackground->setLayer($this->layers['land']);
                $mapItemBackground->setContext($context);
                $mapItemBackground->setX($columnIndex);
                $mapItemBackground->setY($rowIndex);
                $this->manager->persist($mapItemBackground);

                $isBlack = !$isBlack;
                $columnIndex++;
            }
            $rowIndex++;
        }
        $map = new Map();
        $map->setName('chess_basic_map');
        $map->setLabel('Chess basic map');
        $map->setCellSize(64);
        $map->setType('square');
        $map->setPencilSets([$pencilSet]);
        $map->setLayers($layers);
        $this->manager->persist($map);

        $context->setMap($map);
        $this->manager->persist($context);
        $this->manager->flush();

        foreach (['black', 'white'] as $color) {
            foreach (['rook', 'knight', 'bishop', 'queen', 'king', 'pawn'] as $type) {
                $this->createEntityModel(
                    "chess_{$type}_{$color}",
                    ucwords("{$color} {$type}"),
                    "chess_{$type}",
                    $pieces["{$color}_{$type}"]
                );
            }
        }

        $this->createPiece('chess_rook_black', $context, new Position(0, 0));
        $this->createPiece('chess_rook_black', $context, new Position(7, 0));
        $this->createPiece('chess_knight_black', $context, new Position(1, 0));
        $this->createPiece('chess_knight_black', $context, new Position(6, 0));
        $this->createPiece('chess_bishop_black', $context, new Position(2, 0));
        $this->createPiece('chess_bishop_black', $context, new Position(5, 0));
        $this->createPiece('chess_queen_black', $context, new Position(3, 0));
        $this->createPiece('chess_king_black', $context, new Position(4, 0));
        $this->createPiece('chess_pawn_black', $context, new Position(0, 1));
        $this->createPiece('chess_pawn_black', $context, new Position(1, 1));
        $this->createPiece('chess_pawn_black', $context, new Position(2, 1));
        $this->createPiece('chess_pawn_black', $context, new Position(3, 1));
        $this->createPiece('chess_pawn_black', $context, new Position(4, 1));
        $this->createPiece('chess_pawn_black', $context, new Position(5, 1));
        $this->createPiece('chess_pawn_black', $context, new Position(6, 1));
        $this->createPiece('chess_pawn_black', $context, new Position(7, 1));

        $this->createPiece('chess_rook_white', $context, new Position(0, 7));
        $this->createPiece('chess_rook_white', $context, new Position(7, 7));
        $this->createPiece('chess_knight_white', $context, new Position(1, 7));
        $this->createPiece('chess_knight_white', $context, new Position(6, 7));
        $this->createPiece('chess_bishop_white', $context, new Position(2, 7));
        $this->createPiece('chess_bishop_white', $context, new Position(5, 7));
        $this->createPiece('chess_queen_white', $context, new Position(3, 7));
        $this->createPiece('chess_king_white', $context, new Position(4, 7));
        $this->createPiece('chess_pawn_white', $context, new Position(0, 6));
        $this->createPiece('chess_pawn_white', $context, new Position(1, 6));
        $this->createPiece('chess_pawn_white', $context, new Position(2, 6));
        $this->createPiece('chess_pawn_white', $context, new Position(3, 6));
        $this->createPiece('chess_pawn_white', $context, new Position(4, 6));
        $this->createPiece('chess_pawn_white', $context, new Position(5, 6));
        $this->createPiece('chess_pawn_white', $context, new Position(6, 6));
        $this->createPiece('chess_pawn_white', $context, new Position(7, 6));
    }

    protected function createPiece($name, Context $context, Position $position)
    {
        if (!isset($this->entityModels[$name])) {
            throw new UnexpectedValueException("Entity model not found : {$name}");
        }
        $this
            ->container
            ->get('bluebear.manager.entity_instance')
            ->create($context, $this->entityModels[$name], $position, $this->layers['units']);
    }

    /**
     * @param string $name
     * @param string $label
     * @param string $type entity model type
     * @param Pencil $pencil
     */
    protected function createEntityModel($name, $label, $type, Pencil $pencil)
    {
        $entityModel = new EntityModel();
        $entityModel->setName($name);
        $entityModel->setLabel($label);
        $entityModel->setType($type);
        $entityModel->setPencil($pencil);
        $this->entityModelManager->save($entityModel);
        $this->entityModels[$name] = $entityModel;
    }
}
