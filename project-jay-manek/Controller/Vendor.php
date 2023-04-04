<?php 
class Controller_Vendor extends Controller_Core_Action
{
	public function gridAction()
	{
		try {
			$this->getMessage()->getSession()->start();
			$query = "SELECT * FROM `vendor` ORDER BY `first_name` DESC;";
			$vendors =  Ccc::getModel('Vendor_Row')->fetchAll($query);

			$this->getView()
				->setTemplate('vendor/grid.phtml')
				->setData(['vendors' => $vendors]);
			$this->render();
		} catch (Exception $e) {

		}
	}

	public function addAction()
	{
		try {
			$vendor =  Ccc::getModel('Vendor_Row');
			$vendorAddress =  Ccc::getModel('Vendor_Row');
			$this->getView()
				->setTemplate('vendor/edit.phtml')
				->setData(['vendor' => $vendor, 'vendorAddress' => $vendorAddress]);
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

			if (!($vendor =  Ccc::getModel('Vendor_Row')->load($id))) {
				throw new Exception("Invalid Id.", 1);
			}

			$vendorAddress =  Ccc::getModel('Vendor_Row');
			$vendorAddress->getTable()->setTableName('vendor_address');
			if (!$vendorAddress->load($id)) {
				throw new Exception("Invalid Id.", 1);
			}

			$this->getView()
				->setTemplate('vendor/edit.phtml')
				->setData(['vendor' => $vendor, 'vendorAddress' => $vendorAddress]);
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

			if (!($postData = $this->getRequest()->getPost('vendor'))) {
				throw new Exception("Invalid data posted.", 1);
			}

			if ($id = (int) $this->getRequest()->getParem('id')) {
				if (!($vendor =  Ccc::getModel('Vendor_Row')->load($id))) {
					throw new Exception("Invalid Id.", 1);
				}

				$vendor->updated_at = date("y-m-d H:i:s");
			}
			else {
				$vendor =  Ccc::getModel('Vendor_Row');
				$vendor->created_at = date("y-m-d H:i:s");
			}

			if (!($insertId = $vendor->setData($postData)->save())) {
				throw new Exception("Unable to save.", 1);
			}

			if (!($postData = $this->getRequest()->getPost('vendorAddress'))) {
				throw new Exception("Invalid data posted.", 1);
			}

			if ($id = (int) $this->getRequest()->getParem('id')) {
				$vendorAddress =  Ccc::getModel('Vendor_Row');
				$vendorAddress->getTable()->setTableName('vendor_address');
				if (!$vendorAddress->load($id)) {
					throw new Exception("Invalid Id.", 1);
				}

				$vendorAddress->vendor_id = $id;
			}
			else {
				$vendorAddress =  Ccc::getModel('Vendor_Row');
				$vendorAddress->getTable()->setTableName('vendor_address')->setPrimaryKey('address_id');
				$vendorAddress->vendor_id = $insertId;
			}

			if (!$vendorAddress->setData($postData)->save()) {
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

			if (!($vendor =  Ccc::getModel('Vendor_Row')->load($id))) {
				throw new Exception("Invalid Id.", 1);
			}

			if (!$vendor->delete()) {
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