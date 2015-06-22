<?php

namespace BlueBear\EngineBundle\Behavior;


trait GameCharacterTrait
{
    /**
     * @ORM\Column(name="race_code", type="string", length=255)
     */
    protected $raceCode;

    /**
     * @ORM\Column(name="class_code", type="string", length=255)
     */
    protected $classCode;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\Column(name="hit_points", type="integer")
     */
    protected $hitPoints;

    /**
     * @return mixed
     */
    public function getRaceCode()
    {
        return $this->raceCode;
    }

    /**
     * @param mixed $raceCode
     */
    public function setRaceCode($raceCode)
    {
        $this->raceCode = $raceCode;
    }

    /**
     * @return mixed
     */
    public function getClassCode()
    {
        return $this->classCode;
    }

    /**
     * @param mixed $classCode
     */
    public function setClassCode($classCode)
    {
        $this->classCode = $classCode;
    }

    /**
     * @return mixed
     */
    public function getHitPoints()
    {
        return $this->hitPoints;
    }

    /**
     * @param mixed $hitPoints
     */
    public function setHitPoints($hitPoints)
    {
        $this->hitPoints = $hitPoints;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}
