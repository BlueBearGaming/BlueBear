<?php

namespace BlueBear\DungeonBundle\Entity\Dice;


class DiceLauncher
{
    public function launch($diceCode)
    {
        $diceArray = explode('d', $diceCode);
        $numberOfDice = $diceArray[0];
        $size = $diceArray[1];
        $dices = [];

        while ($numberOfDice) {
            $dice = new Dice();
            $dice->size = (int)$size;
            $dice->value = rand(1, $size);

            $dices[] = $dice;
            $numberOfDice--;
        }
        if (count($dices) == 1) {
            $dices = $dices[0];
        }
        return $dices;
    }

    /**
     * @param Dice[] $dices
     */
    public function removeLowest(array &$dices)
    {
        $lowestValue = 1;
        $lowestValueIndex = 0;

        foreach ($dices as $index => $dice) {
            if ($dice->value <= $lowestValue) {
                $lowestValueIndex = $index;
                $lowestValue = $dice->value;
            }
        }
        unset ($dices[$lowestValueIndex]);
    }

    /**
     * @param Dice[] $dices
     * @return int
     */
    public function sum(array $dices)
    {
        $sum = 0;

        foreach ($dices as $dice) {
            $sum += (int)$dice->value;
        }
        return $sum;
    }
}
