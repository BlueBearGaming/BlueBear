<?php

namespace BlueBear\GameChessBundle\DataFixtures\ORM;

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
use BlueBear\EngineBundle\Entity\EntityModelAttribute;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\File\File;

class ChessGameData implements FixtureInterface, ContainerAwareInterface
{
    use ContainerTrait;
    
    /**
     * @var ObjectManager
     */
    protected $manager;

    protected $layers = [];

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
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

        $finder = new Finder();
        $finder->files()->in($applicationPath . '/../fixtures/chess');
        $pieces = [];
        /** @var SplFileInfo $fileInfo */
        foreach ($finder as $fileInfo) {
            /** @var Image $image */
            $image = $imageManager->addFile(new File($fileInfo->getRealPath()), $fileInfo->getRealPath(), 'image');
            $pencilType = strpos($fileInfo->getFilename(), 'bg') ? 'land' : 'units';
            $piecePencil = new Pencil();
            $piecePencil->setName($fileInfo->getFilename());
            $piecePencil->setType($pencilType);
            $piecePencil->setImage($image);
            $piecePencil->setPencilSet($pencilSet);

            $this->manager->persist($piecePencil);
            $pieces[str_replace('.png', '', $fileInfo->getFilename())] = $piecePencil;
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
        $isBlack = false;

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

        $this->createPiece('black_rook_left', 'Black Rook', $pieces['black_rook'], $context, new Position(0, 0));
        $this->createPiece('black_rook_right', 'Black Rook', $pieces['black_rook'], $context, new Position(7, 0));
        $this->createPiece('black_knight_left', 'Black Knight', $pieces['black_knight'], $context, new Position(1, 0));
        $this->createPiece('black_knight_right', 'Black Knight', $pieces['black_knight'], $context, new Position(6, 0));
        $this->createPiece('black_bishop_left', 'Black Bishop Left', $pieces['black_bishop'], $context, new Position(2, 0));
        $this->createPiece('black_bishop_right', 'Black Bishop Right', $pieces['black_bishop'], $context, new Position(5, 0));
        $this->createPiece('black_queen', 'Black Queen', $pieces['black_queen'], $context, new Position(3, 0));
        $this->createPiece('black_king', 'Black King', $pieces['black_king'], $context, new Position(4, 0));
        $this->createPiece('black_pawn_1', 'Black Pawn', $pieces['black_pawn'], $context, new Position(0, 1));
        $this->createPiece('black_pawn_2', 'Black Pawn', $pieces['black_pawn'], $context, new Position(1, 1));
        $this->createPiece('black_pawn_3', 'Black Pawn', $pieces['black_pawn'], $context, new Position(2, 1));
        $this->createPiece('black_pawn_4', 'Black Pawn', $pieces['black_pawn'], $context, new Position(3, 1));
        $this->createPiece('black_pawn_5', 'Black Pawn', $pieces['black_pawn'], $context, new Position(4, 1));
        $this->createPiece('black_pawn_6', 'Black Pawn', $pieces['black_pawn'], $context, new Position(5, 1));
        $this->createPiece('black_pawn_7', 'Black Pawn', $pieces['black_pawn'], $context, new Position(6, 1));
        $this->createPiece('black_pawn_8', 'Black Pawn', $pieces['black_pawn'], $context, new Position(7, 1));

        $this->createPiece('white_rook_left', 'White Rook', $pieces['white_rook'], $context, new Position(0, 7));
        $this->createPiece('white_rook_right', 'White Rook', $pieces['white_rook'], $context, new Position(7, 7));
        $this->createPiece('white_knight_left', 'White Knight', $pieces['white_knight'], $context, new Position(1, 7));
        $this->createPiece('white_knight_right', 'White Knight', $pieces['white_knight'], $context, new Position(6, 7));
        $this->createPiece('white_bishop_left', 'White Bishop Left', $pieces['white_bishop'], $context, new Position(2, 7));
        $this->createPiece('white_bishop_right', 'White Bishop Right', $pieces['white_bishop'], $context, new Position(5, 7));
        $this->createPiece('white_queen', 'White Queen', $pieces['white_queen'], $context, new Position(4, 7));
        $this->createPiece('white_king', 'White King', $pieces['white_king'], $context, new Position(3, 7));
        $this->createPiece('white_pawn_1', 'White Pawn', $pieces['white_pawn'], $context, new Position(0, 6));
        $this->createPiece('white_pawn_2', 'White Pawn', $pieces['white_pawn'], $context, new Position(1, 6));
        $this->createPiece('white_pawn_3', 'White Pawn', $pieces['white_pawn'], $context, new Position(2, 6));
        $this->createPiece('white_pawn_4', 'White Pawn', $pieces['white_pawn'], $context, new Position(3, 6));
        $this->createPiece('white_pawn_5', 'White Pawn', $pieces['white_pawn'], $context, new Position(4, 6));
        $this->createPiece('white_pawn_6', 'White Pawn', $pieces['white_pawn'], $context, new Position(5, 6));
        $this->createPiece('white_pawn_7', 'White Pawn', $pieces['white_pawn'], $context, new Position(6, 6));
        $this->createPiece('white_pawn_8', 'White Pawn', $pieces['white_pawn'], $context, new Position(7, 6));
    }

    protected function createPiece($name, $label, Pencil $pencil, Context $context, Position $position)
    {
        $attribute = new EntityModelAttribute();
        $attribute->setName('movement');
        $attribute->setType('movement');
        $attribute->setValue(1);
        $this->manager->persist($attribute);

        $whiteRook = new EntityModel();
        $whiteRook->setName($name);
        $whiteRook->setLabel($label);
        $whiteRook->setType('units');
        $whiteRook->setPencil($pencil);
        $whiteRook->setBehaviors([
            'selectable'
        ]);
        $whiteRook->addAttribute($attribute);
        $this->manager->persist($whiteRook);
        $this
            ->container
            ->get('bluebear.manager.entity_instance')
            ->create($context, $whiteRook, $position, $this->layers['units']);
        $this->manager->flush();
    }
}