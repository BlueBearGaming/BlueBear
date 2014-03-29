<?php


namespace BlueBear\BackofficeBundle\Utils\Sprite;

class SpriteSplitter
{
    public function split($imagePath, $destinationDirectory, $splitRules = null)
    {
        if (!count($splitRules)) {
            $splitRules = $this->getDefaultsRules();
        }
        $sourceSize = getimagesize($imagePath);
        $sourceWidth = $sourceSize[0];
        $sourceHeight = $sourceSize[1];
        $source = imagecreatefromstring(file_get_contents($imagePath));
        $x = 0;
        $index = 0;


        while ($x < $sourceWidth) {
            $y = 0;
            $destination = imagecreatetruecolor($splitRules->width, $splitRules->height);

            while ($y < $sourceHeight) {
                imagecopy(
                    $destination,
                    $source,
                    $splitRules->destinationX,
                    $splitRules->destinationY,
                    $x,
                    $y,
                    $splitRules->width,
                    $splitRules->height
                );
                $destinationFileName = $destinationDirectory . '/' . 'character_' . $index . '.png';
                imagepng($destination, $destinationFileName);

                $y += $splitRules->height;
                $index++;
            }
            $x += $splitRules->width;
        }
    }

    /**
     * Return RPG Maker sprite splitting rules
     *
     * @return SplitRules
     */
    protected function getDefaultsRules()
    {
        $rules = new SplitRules();
        $rules->destinationX = 0;
        $rules->destinationY = 0;
        $rules->width = 32;
        $rules->height = 48;

        return $rules;
    }
} 