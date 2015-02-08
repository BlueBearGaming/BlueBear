<?php

namespace Sidus\SidusBundle\Action;

use Sidus\SidusBundle\Permission\PermissionMask;

interface ActionInterface {

	public function getRoute();

	public function getPermissionMask();

	public function getClass();

	public function getIsLocalized();

	public function getIsVersionned();

	public function getTitle();

	public function getIconClass();

	public function getName();
	
	public function setRoute($route);

	public function setPermissionMask(PermissionMask $mask);

	public function setClass($class);

	public function setIsLocalized($isLocalized);

	public function setIsVersionned($isVersionned);

	public function setTitle($title);

	public function setIconClass($iconClass);
}
