<?php
class Controller_Core_Front
{
	public function init()
	{
		$request = Ccc::getModel('Core_Request');
		$controllerName = $request->getControllerName();
		$controllerClassName = 'Controller_'.ucwords($controllerName,'_');
		$controllerClassPath = str_replace('_', '/', $controllerClassName);
		require_once "{$controllerClassPath}.php";
		$controller = new $controllerClassName();
		$actionName = $request->getActionName().'Action';
		if (method_exists($request, $actionName)) {
			throw new Exception("{$actionName} doesnot exists.", 1);
			
		}
		$controller->$actionName();
        
	}
}
?>