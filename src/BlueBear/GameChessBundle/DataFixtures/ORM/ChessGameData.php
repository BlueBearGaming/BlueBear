<?php

namespace BlueBear\GameChessBundle\DataFixtures\ORM;

use BlueBear\BaseBundle\Behavior\ContainerTrait;
use BlueBear\CoreBundle\Entity\Editor\Image;
use BlueBear\CoreBundle\Entity\Map\Context;
use BlueBear\CoreBundle\Entity\Map\Map;
use BlueBear\CoreBundle\Entity\Map\MapItem;
use BlueBear\CoreBundle\Entity\Map\Pencil;
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

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->createMapItems();
    }

    protected function createMapItems()
    {
        $imageManager = $this->container->get('bluebear.manager.image');
        $applicationPath = $this->container->get('kernel')->getRootDir();
        $finder = new Finder();
        $finder->files()->in($applicationPath . '/../fixtures');
        $pieces = [];
        /** @var SplFileInfo $fileInfo */
        foreach ($finder as $fileInfo) {
            /** @var Image $image */
            $image = $imageManager->addFile(new File($fileInfo->getRealPath()), $fileInfo->getFilename());
            $piecePencil = new Pencil();
            $piecePencil->setImage($image);
            $this->manager->persist($piecePencil);
            $pieces[$fileInfo->getFilename()] = $piecePencil;
        }
        $layers = $this
            ->container
            ->get('bluebear.manager.layer')
            ->findAll();
        $rowIndex = 0;
        $columnIndex = 0;
        $context = new Context();
        $this->manager->persist($context);
        $isBlack = true;

        while ($rowIndex < 8) {
            while ($columnIndex < 8) {
                $piece = $this->getPieceForPosition($columnIndex, $rowIndex);

                if ($piece) {
                    $mapItemPiece = new MapItem();
                    $mapItemPiece->setPencil($pieces[$piece]);
                    $mapItemPiece->setLayer($layers['units']);
                    $mapItemPiece->setContext($context);
                    $this->manager->persist($mapItemPiece);
                    // TODO listeners
                }
                $mapItemBackground = new MapItem();
                $mapItemBackground->setPencil($isBlack ? $pieces['black_bg'] : $pieces['white_bg']);
                $mapItemBackground->setLayer($layers['background']);
                $mapItemBackground->setContext($context);
                $this->manager->persist($mapItemBackground);

                $isBlack = !$isBlack;
                $columnIndex++;
            }
            $rowIndex++;
        }
        $map = new Map();
        $map->setName('Chess basic map');
        $map->setContexts([$context]);
        $this->manager->persist($map);
    }

    protected function getPieceForPosition($x, $y)
    {
        $piece = null;

        if (($x == 0 and $y == 0) or ($x == 7 and $y == 0)) {
            $piece = 'white_rook';
        } else if (($x == 0 and $y == 7) or ($x == 7 and $y == 7)) {
            $piece = 'black_rook';
        } else if (($x == 1 and $y == 0) or ($x == 6 and $y == 0)) {
            $piece = 'white_knight';
        } else if (($x == 1 and $y == 7) or ($x == 6 and $y == 7)) {
            $piece = 'black_knight';
        } else if (($x == 2 and $y == 0) or ($x == 5 and $y == 0)) {
            $piece = 'white_bishop';
        } else if (($x == 2 and $y == 7) or ($x == 5 and $y == 7)) {
            $piece = 'black_bishop';
        } else if (($x == 3 and $y == 0)) {
            $piece = 'white_queen';
        } else if (($x == 4 and $y == 7)) {
            $piece = 'black_queen';
        } else if (($x == 4 and $y == 0)) {
            $piece = 'white_king';
        }  else if (($x == 3 and $y == 7)) {
            $piece = 'black_king';
        } else if ($x == 1) {
            $piece = 'white_pawn';
        } else if ($x == 2) {
            $piece = 'black_pawn';
        }
        return $piece;
    }
}