<?php
class Model_Core_Message
{
	const SUCCESS = 'success';
	const FAILURE = 'failure';
	const NOTICE = 'notice';
	protected $session = null;

	function __construct()
	{
		$this->getSession();
	}

	public function setSession($session)
	{
		$this->session = $session;
		return $this;
	}

	public function getSession()
	{
		if ($this->session) {
			return $this->session;
		}

		$session = Ccc::getModel('Core_Session');
		$this->setSession($session);
		return $this->session;
	}

	public function addMessage($message, $type = self::SUCCESS)
	{
		if (!$this->getSession()->has('message')) {
			$this->getSession()->set('message', []);
		}

		$messages = $this->getMessages();
		$messages[$type] = $message;
		$this->getSession()->set('message', $messages);
		return $this;
	}

	public function clearMessage()
	{
		$this->getSession()->set('message', []);
		return $this;
	}

	public function getMessages()
	{
		if (!$this->getSession()->has('message')) {
			return null;
		}

		return $this->getSession()->get('message');
	}
	
}
?>