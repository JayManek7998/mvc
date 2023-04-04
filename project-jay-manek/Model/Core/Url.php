<?php

class Model_Core_Url
{
	
	public function getCurrentUrl()
	{
		return $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}

	public function getUrl($action = null, $controller = null, $params = [], $resetParam = false)
	{
		$request = Ccc::getModel('Core_Request');
		$final = $request->getParem();

		if ($resetParam) {
			$final = [];
		}

		if ($controller) {
			$final['c'] = $controller;
		}
		else {
			$final['c'] = $request->getControllerName();
		}

		if ($action) {
			$final['a'] = $action;
		}
		else {
			$final['a'] = $request->getActionName();
		}

		if ($params) {
			$final = array_merge($final, $params);
		}

		$queryString = http_build_query($final);
		$url = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?" . $queryString;
		return $url;

	}

}

?>