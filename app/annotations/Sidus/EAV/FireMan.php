<?php namespace Sidus\EAV; abstract class FireMan extends \BlueBear\FireBundle\Entity\EAVEntity {
/**
 * @return string
 */
abstract public function getName();
/**
 * @param string $value
 * @return FireMan
 */
abstract public function setName($value);
/**
 * @return integer
 */
abstract public function getAge();
/**
 * @param integer $value
 * @return FireMan
 */
abstract public function setAge($value);
/**
 * @return string
 */
abstract public function getClass();
/**
 * @param string $value
 * @return FireMan
 */
abstract public function setClass($value);
}
