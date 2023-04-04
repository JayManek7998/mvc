<?php
class Controller_Shipping extends Controller_Core_Action
{
	public function gridAction()
	{
		try {
			$this->getMessage()->getSession()->start();
			$query = "SELECT * FROM `shipping_method` ORDER BY `name` DESC;";
			$shippings =  Ccc::getModel('Shipping_Row')->fetchAll($query);

			$this->getView()
				->setTemplate('shipping_method/grid.phtml')
				->setData(['shippings' => $shippings]);
			$this->render();
		} catch (Exception $e) {

		}
	}

	public function addAction()
	{
		try {
			$shipping =  Ccc::getModel('Shipping_Row');
			$this->getView()
				->setTemplate('shipping_method/edit.phtml')
				->setData(['shipping' => $shipping]);
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

			if (!($shipping =  Ccc::getModel('Shipping_Row')->load($id))) {
				throw new Exception("Invalid Id.", 1);
			}

			$this->getView()
				->setTemplate('shipping_method/edit.phtml')
				->setData(['shipping' => $shipping]);
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

			if (!($postData = $this->getRequest()->getPost('shipping'))) {
				throw new Exception("Invalid data posted.", 1);
			}

			if ($id = (int) $this->getRequest()->getParem('id')) {
				if (!($shipping =  Ccc::getModel('Shipping_Row')->load($id))) {
					throw new Exception("Invalid Id.", 1);
				}

				$shipping->updated_at = date("y-m-d H:i:s");
			}
			else {
				$shipping =  Ccc::getModel('Shipping_Row');
				$shipping->created_at = date("y-m-d H:i:s");
			}

			if (!$shipping->setData($postData)->save()) {
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

			if (!($shipping =  Ccc::getModel('Shipping_Row')->load($id))) {
				throw new Exception("Invalid Id.", 1);
			}

			if (!$shipping->delete()) {
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