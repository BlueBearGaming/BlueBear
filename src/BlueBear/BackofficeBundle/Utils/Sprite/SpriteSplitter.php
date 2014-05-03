<?php


namespace BlueBear\BackofficeBundle\Utils\Sprite;

class SpriteSplitter
{
    /**
     * Split a sprite into independent file
     *
     * @param $imagePath
     * @param $destinationDirectory
     * @param null $splitRules
     * @return array array of images path
     * @throws \Exception
     */
    public function split($imagePath, $destinationDirectory, $splitRules = null)
    {
        if (!count($splitRules)) {
            $splitRules = $this->getDefaultsRules();
        }
        // get image size
        $sourceSize = getimagesize($imagePath);
        $sourceWidth = $sourceSize[0];
        $sourceHeight = $sourceSize[1];
        // get source sprite
        $source = imagecreatefromstring(file_get_contents($imagePath));
        $x = 0;
        $index = 0;
        $images = [];

        // row process
        while ($x < $sourceWidth) {
            $y = 0;
            // create an image resource
            $destination = imagecreatetruecolor($splitRules->width, $splitRules->height);

            // sprite process
            while ($y < $sourceHeight) {
                // cut source sprite according to the rules
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
                // create destination image
                $filename = 'character_' . $index . '.png';
                $destinationFilePath = $destinationDirectory . '/' . $filename;
                $success = imagepng($destination, $destinationFilePath);

                if (!$success) {
                    throw new \Exception('An error has occurred during png creation');
                }
                $images[$filename] = $destinationFilePath;

                // increase y index according to the height
                $y += $splitRules->height;
                $index++;
            }
            // increase x index according to the width
            $x += $splitRules->width;
        }
        return $images;
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