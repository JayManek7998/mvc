<?php
class Controller_Payment extends Controller_Core_Action
{
	public function gridAction()
	{
		try {
			$this->getMessage()->getSession()->start();
			$query = "SELECT * FROM `payment_method` ORDER BY `name` DESC;";
			$payments =  Ccc::getModel('Payment_Row')->fetchAll($query);

			$this->getView()
				->setTemplate('payment_method/grid.phtml')
				->setData(['payments' => $payments]);
			$this->render();
		} catch (Exception $e) {

		}
	}

	public function addAction()
	{
		try {
			$payment =  Ccc::getModel('Payment_Row');
			$this->getView()
				->setTemplate('payment_method/edit.phtml')
				->setData(['payment' => $payment]);
			$this->render();
		} catch (Exception $e) {
			
		}
	}

	public function editAction()
	{
		try {
			$this->getMessage()->getSession()->start();
			if (!($id = (int) $this->getRequest()->getParem('id'))) {
				throw new Exception("Invalid request.", 1);
			}

			if (!($payment =  Ccc::getModel('Payment_Row')->load($id))) {
				throw new Exception("Invalid Id.", 1);
			}

			$this->getView()
				->setTemplate('payment_method/edit.phtml')
				->setData(['payment' => $payment]);
			$this->render();
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(),Model_Core_Message::FAILURE);
			$this->redirect('grid');
		}
	}

	public function saveAction()
	{
		try {
			$this->getMessage()->getSession()->start();
			if (!$this->getRequest()->isPost()) {
				throw new Exception("Invalid request.", 1);
			}

			if (!($postData = $this->getRequest()->getPost('payment'))) {
				throw new Exception("Invalid data posted.", 1);
			}

			if ($id = (int) $this->getRequest()->getParem('id')) {
				if (!($payment =  Ccc::getModel('Payment_Row')->load($id))) {
					throw new Exception("Invalid Id.", 1);
				}

				$payment->updated_at = date("y-m-d H:i:s");
			}
			else {
				$payment =  Ccc::getModel('Payment_Row');
				$payment->created_at = date("y-m-d H:i:s");
			}

			if (!$payment->setData($postData)->save()) {
				throw new Exception("Unable to save.", 1);
			}

			$this->getMessage()->addMessage('Data saved successfully.');
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(),Model_Core_Message::FAILURE);
		}
		
		$this->redirect('grid', null, [], true);
	}

	public function deleteAction()
	{
		try {
			$this->getMessage()->getSession()->start();
			if (!($id = (int) $this->getRequest()->getParem('id'))) {
				throw new Exception("Invalid request.", 1);
			}

			if (!($payment =  Ccc::getModel('Payment_Row')->load($id))) {
				throw new Exception("Invalid Id.", 1);
			}

			if (!$payment->delete()) {
				throw new Exception("Unable to delete.", 1);
			}

			$this->getMessage()->addMessage('Data deleted successfully.');
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(),Model_Core_Message::FAILURE);
		}

		$this->redirect('grid', null, [], true);
	}

}

?>