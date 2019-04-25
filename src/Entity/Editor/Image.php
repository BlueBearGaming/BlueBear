<?php


namespace App\Entity\Editor;

use App\Entity\Behavior\Timestampable;
use App\Entity\Map\Pencil;
use App\Entity\Map\PencilSet;

/**
 * Image are used in the editor
 */
class Image
{
    use Timestampable;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $path;

    /**
     * @var Pencil
     */
    protected $pencil;

    /**
     * @var PencilSet
     */
    protected $pencilSet;

    public function getType(): string
    {
        return 'image';
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * @return Pencil
     */
    public function getPencil(): Pencil
    {
        return $this->pencil;
    }

    /**
     * @param Pencil $pencil
     */
    public function setPencil(Pencil $pencil): void
    {
        $this->pencil = $pencil;
    }

    /**
     * @return PencilSet
     */
    public function getPencilSet(): PencilSet
    {
        return $this->pencilSet;
    }

    /**
     * @param PencilSet $pencilSet
     */
    public function setPencilSet(PencilSet $pencilSet): void
    {
        $this->pencilSet = $pencilSet;
    }
} 
