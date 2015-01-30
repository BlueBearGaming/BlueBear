<?php

namespace BlueBear\BaseBundle\Behavior;

/**
 * StringUtilsTrait
 *
 * String utils behavior
 */
trait StringUtilsTrait
{
    /**
     * Underscore a string ("MyEntity" => "my_entity")
     *
     * @param $id
     * @return string
     */
    public function underscore($id)
    {
        return strtolower(preg_replace(['/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'], ['\\1_\\2', '\\1_\\2'], strtr($id, '_', '_')));
    }

    /**
     * Inflect and camelize a string ("my_entity" => "My Entity")
     *
     * @param $string
     * @return string
     */
    protected function inflectString($string)
    {
        return strtr(ucwords(strtr($string, ['_' => ' ', '.' => '_ ', '\\' => '_ '])), [' ' => '']);
    }
}