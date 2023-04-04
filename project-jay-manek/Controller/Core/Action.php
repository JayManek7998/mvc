<?php

class Controller_Core_Action
{
	protected $adapter = null;
	protected $message = null;
	protected $request = null;
	protected $url = null;
	protected $view = null;

	public function setAdapter($adapter)
	{
		$this->adapter = $adapter;
		return $this;
	}

	public function getAdapter()
	{
		if ($this->adapter) {
			return $this->adapter;
		}

		$adapter = Ccc::getModel('Core_Adapter');
		$this->setAdapter($adapter);
		return $adapter;
	}

	public function setMessage($message)
	{
		$this->message = $message;
		return $this;
	}

	public function getMessage()
	{
		if ($this->message) {
			return $this->message;
		}

		$message = Ccc::getModel('Core_Message');
		$this->setMessage($message);
		return $message;
	}

	public function setRequest($request)
	{
		$this->request = $request;
		return $this;
	}

	public function getRequest()
	{
		if ($this->request) {
			return $this->request;
		}

		$request = Ccc::getModel('Core_Request');
		$this->setRequest($request);
		return $request;
	}

	public function setUrl($url)
	{
		$this->url = $url;
		return $this;
	}

	public function getUrl()
	{
		if ($this->url) {
			return $this->url;
		}

		$url = Ccc::getModel('Core_Url');
		$this->setUrl($url);
		return $url;
	}

	public function setView($view)
	{
		$this->view = $view;
		return $this;	
	}

	public function getView()
	{
		if ($this->view) {
			return $this->view;
		}

		$view = Ccc::getModel('Core_View');
		$this->setView($view);
		return $view;
	}

	public function redirect($action = null, $controller = null, $params = [], $resetParam = false)
	{
		$url = $this->getUrl()->getUrl($action, $controller, $params, $resetParam);
		header("location: $url");
		exit();
	}

	public function render()
	{
		return $this->getView()->render();
	}

}

?>