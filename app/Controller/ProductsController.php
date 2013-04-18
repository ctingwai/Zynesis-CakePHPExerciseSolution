<?php
/**
 * Controller for products
 * */
class ProductsController extends AppController {
	public $components = array('Image');

	/**
	 * The maximum upload file size in Megabytes
	 * Default = 2 Megabytes;
	 * Make sure that you change upload_max_filesize directive to allow maximum upload size
	 * */
	private $MAX_FILE_SIZE = 2;

	/**
	 * Sorting variables
	 * */
	private $SORT_NAME = 0;
	private $SORT_PRICE = 1;
	private $ORDER_ASC = 0;
	private $ORDER_DES = 1;

	/**
	 * Product's landing page
	 * */
	public function index() {
		//Construct a string for ORDER BY MySQL query statement
		$sortSql = '';
		if(!empty($this->request->data)) {
			$sort = $this->request->data['Product']['sort'];
			$order = $this->request->data['Product']['order'];

			if($sort == $this->SORT_NAME)
				$sortSql .= 'title ';
			else
				$sortSql .= 'price ';

			if($order == $this->ORDER_ASC)
				$sortSql .= 'ASC';
			else
				$sortSql .= 'DESC';
		}

		//Sort the product if required
		$this->set(
			'products',
			$this->Product->find('all', array(
				'fields' => array('id', 'title', 'price'),
				'order' => $sortSql
			))
		);
	}

	/**
	 * Add a product
	 * */
	public function add() {
		if($this->request->is('post')) {
			//Search for duplicates
			$duplicateRecords = $this->Product->find(
				'all',
				array(
					'conditions' => array(
						'title' => $this->request->data['Product']['title']
					)
				)
			);

			//Proceed with insertion if no duplicates are found
			if(!$duplicateRecords) {
				$errno = $this->request->data['Product']['image']['error'];
				$filetype = $this->request->data['Product']['image']['type'];
				$filesize = $this->request->data['Product']['image']['size'];
				$tmp = $this->request->data['Product']['image']['tmp_name'];

				$errMsg = $this->Image->uploadError($filetype, $errno, $this->MAX_FILE_SIZE);

				if(!$errMsg) {
					//Upload file
					if($this->Image->isUploaded($errno)) {
						//Move uploaded file to correct directory
						$ext = $this->Image->getExtension($filetype);
						move_uploaded_file(
							$tmp, 'product_img/' . $this->request->data['Product']['title']
							. $this->Image->getExtension($filetype)
						);
					}

					//Write to database
					$this->Product->create();
					if($this->Product->save($this->request->data)) {
						$this->Session->setFlash('Product saved');
						$this->redirect(array('action' => 'add'));
					} else {
						$this->Session->setFlash('An error occured while saving product');
					}
				} else {
					$this->Session->setFlash($errMsg);
				}
			} else {
				$this->Session->setFlash(
					'A duplicate is found for "'
					. $this->request->data['Product']['title']
					. '", please use a different name.');
			}
		}
	}

	/**
	 * View a product details
	 * */
	public function view(){
		//Set product variable for use by view.ctp
		$this->set(
			'product',
			//Find the product where id = named paramater
			$this->Product->find(
				'first',
				array(
					'conditions' => array('id' => $this->passedArgs['pid'])
				)
			)
		);
	}

	/**
	 * Update a product
	 * */
	public function update() {}

	/**
	 * Remove a product
	 * */
	public function remove() {
		//Find out product title
		$title = $this->Product->find('first',
			array(
				'conditions' => array('id' => $this->passedArgs['id']),
				'fields' => array('title')
			)
		);
		$title = $title['Product']['title'];
		//Delete records
		if($this->Product->delete($this->passedArgs['id'], true)) {
			//Remove product image
			$this->Image->rm($title);
			$this->Session->setFlash('Product deleted');
			$this->redirect(array('action' => 'index'));
		} else {
			$this->Session->setFlash('An error has occured while deleting the product');
		}
	}

	/**
	 * Authorization callback
	 * @return False if not authorized
	 * */
	public function isAuthorized($user) {
		//Allow all administrator to add/edit/remove products
		return true;
	}
}
?>
