<?php
class Controller_Product_Media extends Controller_Core_Action
{
	public function gridAction()
	{
		try {
			$this->getMessage()->getSession()->start();
			if (!($product_id = $this->getRequest()->getParem('id'))) {
				throw new Exception("Missing product id.", 1);
			}

			$query = "SELECT * FROM `product_media` WHERE `product_id` = {$product_id} ORDER BY `name` DESC;";
			$medias = Ccc::getModel('Product_Media_Row')->fetchAll($query);

			$this->getView()
				->setTemplate('product/media/grid.phtml')
				->setData(['medias' => $medias]);
			$this->render();
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(),Model_Core_Message::FAILURE);
		}
	}

	public function addAction()
	{
		try {
			$media =  Ccc::getModel('Product_Media_Row');
			$this->getView()
				->setTemplate('product/media/add.phtml')
				->setData(['media' => $media]);
			$this->render();
		} catch (Exception $e) {
			
		}
	}

	public function saveAction()
	{
		try {
			$this->getMessage()->getSession()->start();
			if (!$this->getRequest()->isPost()) {
				throw new Exception("Invalid request.", 1);
			}

			if (!($postData = $this->getRequest()->getPost('media'))) {
				throw new Exception("Invalid data posted.", 1);
			}

			if (!($id = (int) $this->getRequest()->getParem('id'))) {
				throw new Exception("Invalid request.", 1);
			}

			$media =  Ccc::getModel('Product_Media_Row');
			$media->product_id = $id;
			$media->created_at = date("y-m-d H:i:s");

			if (!$media->setData($postData)->save()) {
				throw new Exception("Unable to save.", 1);
			}

			$file = $_FILES['file'];
			$file_name = $file['name'];
			$file_tmp = $file['tmp_name'];
			$file_error = $file['error'];
			$file_size = $file['size'];
			if ($file_error === 0) {
				$file_destination = './Images/'.$file_name;
				move_uploaded_file($file_tmp, $file_destination);
			}

			$this->getMessage()->addMessage('Data saved successfully.');
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(),Model_Core_Message::FAILURE);
		}

		$this->redirect('grid', null, ['id' => $id], true);
	}

	public function operationAction()
	{
		try {
			$this->getMessage()->getSession()->start();
			if ($this->getRequest()->getPost('update')) {
				if (!$this->getRequest()->isPost()) {
					throw new Exception("Invalid request.", 1);
				}

				if (!$id = $this->getRequest()->getParem('product_id')) {
					throw new Exception("Invalid request.", 1);
				}

				$media =  Ccc::getModel('Product_Media_Row');
				$media->setData(['base' => 0, 'small' => 0, 'thumbnail' => 0, 'gallery' => 0])->addData('product_id', $id)->getTable()->setPrimaryKey('product_id');
				$media->save();

				if (!$postData = $this->getRequest()->getPost('base')) {
					throw new Exception("Invalid data posted.", 1);
				}

				if (!Ccc::getModel('Product_Media_Row')->setData(['base' => 1])->addData('media_id', $postData)->save()) {
					throw new Exception("Unable to save.", 1);
				}

				if (!$postData = $this->getRequest()->getPost('small')) {
					throw new Exception("Invalid data posted.", 1);
				}

				if (!Ccc::getModel('Product_Media_Row')->setData(['small' => 1])->addData('media_id', $postData)->save()) {
					throw new Exception("Unable to save.", 1);
				}

				if (!$postData = $this->getRequest()->getPost('thumbnail')) {
					throw new Exception("Invalid data posted.", 1);
				}

				if (!Ccc::getModel('Product_Media_Row')->setData(['thumbnail' => 1])->addData('media_id', $postData)->save()) {
					throw new Exception("Unable to save.", 1);
				}

				if (!$postData = $this->getRequest()->getPost('gallery')) {
					throw new Exception("Invalid data posted.", 1);
				}
				
				if (!Ccc::getModel('Product_Media_Row')->setData(['gallery' => 1])->addData('media_id', $postData)->save()) {
					throw new Exception("Unable to save.", 1);
				}

				$this->getMessage()->addMessage('Data updated successfully.');
			}

			elseif ($this->getRequest()->getPost('deleted')) {
				if (!$id = $this->getRequest()->getParem('product_id')) {
					throw new Exception("Invalid request.", 1);
				}

				if (!Ccc::getModel('Product_Media_Row')->setData(['media_id' => $this->getRequest()->getPost('delete')])->delete()) {
					throw new Exception("Unable to delete the record.", 1);
				}

				$this->getMessage()->addMessage('Data deleted successfully.');
			}
			
		} catch (Exception $e) {
			$this->getMessage()->addMessage($e->getMessage(),Model_Core_Message::FAILURE);
		}

		$this->redirect('grid', null, ['id' => $id], true);
	}

}

?>