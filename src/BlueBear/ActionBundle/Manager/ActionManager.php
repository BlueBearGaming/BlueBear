<?php

namespace Sidus\SidusBundle\Action;

use Sidus\SidusBundle\Entity\Object;
use Symfony\Component\DependencyInjection\Container;

class ActionManager {
	
	/**
	 * @var Container
	 */
	protected $container;
	
	protected static $actions;

	public function __construct(Container $container) {
		$this->container = $container;
	}
	
	public function getActionsForObject(Object $object){
		if($object->hasPermission(\Sidus\SidusBundle\Permission\PermissionMask::ADMIN)){
			return $this->getActions();
		}
		$result = [];
		foreach($this->getActions() as $action){
			if($object->hasPermission($action->getPermissionMask())){
				$result[$action->getName()] = $action;
			}
		}
		return $result;
	}
	
	public function getActions(){
		if(self::$actions){
			return self::$actions;
		}
		$config = $this->container->getParameter('sidus.actions');
		foreach ($config as $key => $value){
			$class_name = isset($value['model_class']) ? $value['model_class'] : '\\Sidus\\SidusBundle\\Action\\DefaultAction';
			self::$actions[$key] = new $class_name($value);
		}
		return self::$actions;
	}
	
	public function getAction($name){
		$actions = $this->getActions();
		if(isset($actions[$name])){
			return $actions[$name];
		}
		return null;
	}
}
