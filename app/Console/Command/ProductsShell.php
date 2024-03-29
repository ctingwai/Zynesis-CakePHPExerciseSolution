<?php
class ProductsShell extends AppShell {
	/**
	 * Uses product model
	 * */
	var $uses = array('Product');
	/**
	 * The image path relative to app/ directory
	 * */
	private $IMAGE_PATH = 'webroot/product_img/';

	public function getOptionParser() {
		$parser = parent::getOptionParser();
		return $parser;
	}

	public function main() {
		switch($this->command) {
			case 'ls':
			case 'list':
				$this->ls();
				break;
			case 'add':
				$this->add();
				break;
			case 'remove':
			case 'delete':
				$this->remove();
				break;
			case 'update':
				$this->update();
				break;
			case 'help':
				$this->help();
				break;
			default:
				$this->out('Unknown command');
				$this->help();
		}
	}

	/**
	 * Print the help function and usage for certain commands
	 * @param command Command usage to print, print general usage if null
	 * */
	private function help($command = null) {
		if(isset($this->args[1]) || isset($command)) {
			if(isset($this->args[1]))
				$command = $this->args[1];
			switch($command) {
				case 'list':
				case 'ls':
					$this->out("Description: List all products");
					$this->out("Usage:\n\tConsole/cake products {list, ls}");
					break;
				case 'add':
					$this->out("Description: Add a product");
					$this->out("Usage:\n\tConsole/cake products add <product's name/title> <price> [image path]");
					break;
				case 'delete':
				case 'remove':
					$this->out("Description: Remove a product");
					$this->out("Usage:\n\tConsole/cake products {remove, delete} <product's ID>");
					break;
				case 'update':
					$this->out('Description: Update an existing product');
					$this->out("Usage:\n\tConsole/cake products update {{title, name} <new name>, price <new price>, image <new image>} <product's ID>");
				default:
					'Command not found';
			}
		} else {
			$this->out("Usage:\n\tConsole/cake products {list, add, remove, delete, update}");
			$this->out("Products management utility\n");
			$this->out("Known Commands:");
			$this->out("\tlist: List products");
			$this->out("\tls: List products");
			$this->out("\tadd: Add a product");
			$this->out("\tremove: Remove a product");
			$this->out("\tdelete: Remove a product");
			$this->out("\tupdate: Update a product");
			$this->out("Please use the following command to find out command specific usage:");
			$this->out("\tConsole/cake products help <command>");
		}
	}

	/**
	 * List all products present in database
	 * */
	private function ls() {
		$products = $this->Product->find('all');
		foreach($products as $product) {
			$this->out("ID: {$product['Product']['id']}\t\"{$product['Product']['title']}\"\t{$product['Product']['price']}");
		}
	}

	/**
	 * Copy image to product_img and format file name automatically
	 * @param path The image path
	 * @param productTitle The product title, used to determine image name
	 * */
	private function cpImg($path, $productTitle) {
		//Check for existing file
		$images = glob("$this->IMAGE_PATH$productTitle.*");
		$proceed = false;
		if(!empty($images)) {
			//Show images that might conflict
			$msg = 'These files are found: ';
			foreach($images as $img)
				$msg .= $img . ' ';
			$this->out($msg);
			while(empty($proceed) || ($proceed != 'y' && $proceed != 'n'))
				$proceed = $this->in('Proceed with deletion? (y/n) ');
			if($proceed == 'y') {
				$proceed = true;
				foreach($images as $img) {
					unlink($img);
				}
			} else {
				$proceed = false;
			}
		}

		//Image checks
		if($proceed && file_exists($path)) {
			$ext = pathinfo($path, PATHINFO_EXTENSION);
			if(isset($ext) && ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'png')) {
				//Copy image
				if(copy($path, $this->IMAGE_PATH . $productTitle . '.' . $ext))
					$this->out('Image copied.');
				else
					$this->out('An error has occured while copying image.');
			} else if(!isset($ext)) {
				//No extension
				$this->out('Unable to determine file extension. Not copying image.');
			} else if($ext != 'jpg' && $ext != 'jpeg' && $ext != 'png' && $ext != 'gif') {
				//Not an image
				$this->out('Not an image. Please use only jpg, jpeg, png and gif images. Not copying image.');
			} else {
				$this->out('An unknown error has occured. Not copying image.');
			}
		} else if(!file_exists($path)) {
			$this->out("File $path not found. Not copying image.");
		}
	}

	/**
	 * Handle add product functionality
	 * */
	private function add() {
		if(count($this->args) >= 3) {
			$title = $this->args[1];
			$price = $this->args[2];

			//Proceed with database operations
			$data = array('Product' => array(
				'title' => $title,
				'price' => $price
			));
			$this->Product->create();
			if($this->Product->save($data)) {
				$this->out('Product added to database');
				//Image operations
				if(isset($this->args[3])) {
					$this->cpImg($this->args[3], $title);
				} else {
					$this->out('Image not supplied. Not copying image.');
				}
			}
		} else {
			$this->out('Missing arguments');
			$this->help('add');
		}
	}

	/**
	 * Handle remove product functionality
	 * */
	private function remove() {
		if(count($this->args) >= 2) {
			$id = $this->args[1];
			$title = $this->Product->find('first', array(
				'conditions' => array('id' => $id),
				'fields' => 'title'
			));

			if(count($title) > 0) {
				$title = $title['Product']['title'];
				//Check for image
				$image = glob("webroot/product_img/$title.*");
				$deleteImg = false;
				if($image) {
					$image = $image[0];
					while(empty($deleteImg) || ($deleteImg != 'y' && $deleteImg != 'n')) {
						$deleteImg = $this->in('Image found. Delete? (y/n) ');
					}
					if($deleteImg == 'y')
						$deleteImg = true;
				}
				//Database deletion
				if($this->Product->delete($id)) {
					$this->out('Product removed from database.');
					//Image deletion
					if($deleteImg && unlink($image)) {
						$this->out('Image deleted.');
					}
				}
			} else {
				$this->out('ID not found, please use "list" command to determine product ID.');
			}
		} else {
			$this->out('Missing arguments');
			$this->help('remove');
		}
	}

	/**
	 * Handle update product functionality
	 * */
	private function update() {
		if(count($this->args) >= 4) {
			$updateField = $this->args[1];
			$val = $this->args[2];
			$id = $this->args[3];
			//Query for product
			$title = $this->Product->find('first', array(
				'fields' => array('title'),
				'conditions' => array('id' => $id)
			));
			$title = $title['Product']['title'];

			if(!empty($title)) {
				//Update operations
				switch($updateField) {
					case 'title':
					case 'name':
						$this->updateField($id, 'title', $val);
						$this->renameImage($title, $val);
						break;
					case 'price':
						$this->updateField($id, 'price', $val);
						break;
					case 'image':
						if(!empty($title)) {
							$this->cpImg($val, $title);
						}
						break;
					default:
						'Unknown field';
						$this->help('update');
				}
			} else {
				$this->out('Product ID not found. Please determine product ID use list command before proceeding.');
				$this->help('list');
			}
		} else {
			$this->out('Missing arguments');
			$this->help('update');
		}
	}

	/**
	 * Rename an image
	 * @param old The old product title
	 * @param new The new product title
	 * */
	private function renameImage($old, $new) {
		$images = glob($this->IMAGE_PATH . $old . '.*');
		if(!empty($images)) {
			$old = $images[0];
			$new = $this->IMAGE_PATH . $new . '.' . pathinfo($old, PATHINFO_EXTENSION);
			if(copy($old, $new) && unlink($old)) {
				$this->out('Image rename successful');
			} else {
				$this->out('An error has occured when updating image.');
			}
		}
	}

	/**
	 * Update a field
	 * @param id The product id to update
	 * @param field The field name in the database to udpate
	 * @param newValue The new value to be inserted
	 * */
	private function updateField($id, $field, $newValue) {
		$this->Product->id = $id;
		if($this->Product->saveField($field, $newValue))
			$this->out('Field updated');
	}
}
?>
