<?php

namespace Sidus\SidusBundle\Action;

use Sidus\SidusBundle\Permission\PermissionMask;

class DefaultAction implements ActionInterface {

	protected $name = 'show';
	protected $permissionMask = PermissionMask::READ;
	protected $class = '';
	protected $isLocalized = false;
	protected $isVersionned = false;
	protected $title = 'Action Name';
	protected $iconClass = 'icon-question';
	protected $requireUser = false;
	
	public function __construct(array $config) {
		$this->name = $config['name'];
		$this->permissionMask = new PermissionMask($config['permission_mask']);
		$this->iconClass = $config['icon'];
		$this->requireUser = $config['require_user'];
	}

	public function getPermissionMask() {
		return $this->permissionMask;
	}

	public function setPermissionMask(PermissionMask $permissionMask) {
		$this->permissionMask = $permissionMask;
		return $this;
	}

	public function getClass() {
		return $this->class;
	}

	public function setClass($class) {
		$this->class = $class;
		return $this;
	}

	public function getIsLocalized() {
		return $this->isLocalized;
	}

	public function setIsLocalized($isLocalized) {
		$this->isLocalized = $isLocalized;
		return $this;
	}

	public function getIsVersionned() {
		return $this->isVersionned;
	}

	public function setIsVersionned($isVersionned) {
		$this->isVersionned = $isVersionned;
		return $this;
	}

	public function getTitle() {
		return $this->title;
	}

	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}

	public function getIconClass() {
		return $this->iconClass;
	}

	public function setIconClass($iconClass) {
		$this->iconClass = $iconClass;
		return $this;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = (string) $name;
		return $this;
	}
	
	public function getRequireUser() {
		return $this->requireUser;
	}

	public function setRequireUser($requireUser) {
		$this->requireUser = (string) $requireUser;
		return $this;
	}
	
	public function __toString() {
		return (string) $this->name;
	}
	
	public function getRoute() {
		
	}

	public function setRoute($route) {
		
	}

}
