<?php
class Controller_Salesman_Price extends Controller_Core_Action
{
	public function gridAction()
	{
		try {
			$this->getMessage()->getSession()->start();

			if (!($id = (int) $this->getRequest()->getParem('id'))) {
				throw new Exception("Invalid request.", 1);
			}

			$query = "SELECT p.`product_id`, p.`name`, p.`sku`, p.`cost`, p.`price`, p.`status`, (SELECT `salesman_price` FROM `salesman_price` WHERE `product_id` = p.`product_id` AND `salesman_id` = '{$id}') AS salesman_price, (SELECT `entity_id` FROM `salesman_price` WHERE `product_id` = p.`product_id` AND `salesman_id` = '{$id}') AS entity_id  FROM `product` AS p";
			$salesmenPrice =  Ccc::getModel('Salesman_Price_Row')->fetchAll($query);

			$query = "SELECT `salesman_id`, `first_name` FROM `salesman` ORDER BY `first_name` ASC";
			$salesmen = Ccc::getModel('Salesman_Row')->fetchAll($query);

			$this->getView()
				->setTemplate('salesman_price/grid.phtml')
				->setData(['salesmenPrice' => $salesmenPrice, 'salesmen' => $salesmen]);
			$this->render();
		} catch (Exception $e) {

		}
	}

	public function updateAction()
	{
		try {
			$this->getMessage()->getSession()->start();
			if (!$this->getRequest()->isPost()) {
				throw new Exception("Invalid request.", 1);
			}

			if (!($postData = $this->getRequest()->getPost('salesmanPrice'))) {
				throw new Exception("Invalid data posted.", 1);
			}

			if (!$salesmanId = (int) $this->getRequest()->getParem('id')) {
					throw new Exception("Invalid Id.", 1);
			}

			foreach ($postData as $productId => $price) {
				$query = "SELECT * FROM `salesman_price` WHERE `product_id` = '{$productId}' AND `salesman_id` = '{$salesmanId}'";
				if (!Ccc::getModel('Salesman_Price_Row')->getTable()->getAdapter()->fetchRow($query)) {
					$prices = ['salesman_price' => $price, 'salesman_id' => $salesmanId, 'product_id' => $productId];
					Ccc::getModel('Salesman_Price_Row')->setData($prices)->save(); 
				}
				else {
					$prices = ['salesman_price' => $price];
					$condition = ['salesman_id' => $salesmanId, 'product_id'=> $productId];
					Ccc::getModel('Salesman_Price_Row')->getTable()->update($prices, $condition);
				}
			}

			$this->getMessage()->addMessage('Data saved successfully.');
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(),Model_Core_Message::FAILURE);
		}
		
		$this->redirect('grid', null, ['id' => $salesmanId], true);
	}

	public function deleteAction()
	{
		try {
			$this->getMessage()->getSession()->start();
			if (!($salesmanId = (int) $this->getRequest()->getParem('id'))) {
				throw new Exception("Invalid request.", 1);
			}

			if (!($productId = (int) $this->getRequest()->getParem('product_id'))) {
				throw new Exception("Invalid request.", 1);
			}

			$id = ['salesman_id' => $salesmanId, 'product_id' => $productId];
			if (!Ccc::getModel('Salesman_Price_Row')->getTable()->delete($id)) {
				throw new Exception("Unable to delete.", 1);
			}

			$this->getMessage()->addMessage('Data deleted successfully.');
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(),Model_Core_Message::FAILURE);
		}

		$this->redirect('grid', null, ['id' => $salesmanId], true);
	}

}
?>