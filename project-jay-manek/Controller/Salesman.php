<?php 
class Controller_Salesman extends Controller_Core_Action
{
	public function gridAction()
	{
		try {
			$this->getMessage()->getSession()->start();
			$query = "SELECT * FROM `salesman` ORDER BY `first_name` DESC;";
			$salesmans =  Ccc::getModel('Salesman_Row')->fetchAll($query);

			$this->getView()
				->setTemplate('salesman/grid.phtml')
				->setData(['salesmans' => $salesmans]);
			$this->render();
		} catch (Exception $e) {

		}
	}

	public function addAction()
	{
		try {
			$salesman =  Ccc::getModel('Salesman_Row');
			$salesmanAddress =  Ccc::getModel('Salesman_Row');
			$this->getView()
				->setTemplate('salesman/edit.phtml')
				->setData(['salesman' => $salesman, 'salesmanAddress' => $salesmanAddress]);
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

			if (!($salesman =  Ccc::getModel('Salesman_Row')->load($id))) {
				throw new Exception("Invalid Id.", 1);
			}

			$salesmanAddress =  Ccc::getModel('Salesman_Row');
			$salesmanAddress->getTable()->setTableName('salesman_address');
			if (!$salesmanAddress->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			$this->getView()
				->setTemplate('salesman/edit.phtml')
				->setData(['salesman' => $salesman, 'salesmanAddress' => $salesmanAddress]);
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

			if (!($postData = $this->getRequest()->getPost('salesman'))) {
				throw new Exception("Invalid data posted.", 1);
			}

			if ($id = (int) $this->getRequest()->getParem('id')) {
				if (!($salesman =  Ccc::getModel('Salesman_Row')->load($id))) {
					throw new Exception("Invalid Id.", 1);
				}

				$salesman->updated_at = date("y-m-d H:i:s");
			}
			else {
				$salesman =  Ccc::getModel('Salesman_Row');
				$salesman->created_at = date("y-m-d H:i:s");
			}

			if (!($insertId = $salesman->setData($postData)->save())) {
				throw new Exception("Unable to save.", 1);
			}

			if (!($postData = $this->getRequest()->getPost('salesmanAddress'))) {
				throw new Exception("Invalid data posted.", 1);
			}

			if ($id = (int) $this->getRequest()->getParem('id')) {
				$salesmanAddress =  Ccc::getModel('Salesman_Row');
				$salesmanAddress->getTable()->setTableName('salesman_address');
				if (!$salesmanAddress->load($id)) {
					throw new Exception("Invalid Id.", 1);
				}

				$salesmanAddress->salesman_id = $id;
			}
			else {
				$salesmanAddress =  Ccc::getModel('Salesman_Row');
				$salesmanAddress->getTable()->setTableName('salesman_address')->setPrimaryKey('address_id');
				$salesmanAddress->salesman_id = $insertId;
			}

			if (!$salesmanAddress->setData($postData)->save()) {
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

			if (!($salesman =  Ccc::getModel('Salesman_Row')->load($id))) {
				throw new Exception("Invalid Id.", 1);
			}

			if (!$salesman->delete()) {
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