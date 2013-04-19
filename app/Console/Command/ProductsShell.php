<?php
class ProductsShell extends AppShell {
	var $uses = array('Product');
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
			case 'edit':
				$this->edit();
				break;
			case 'help':
				$this->help();
				break;
			default:
				$this->out('Unknown command');
				$this->help();
		}
	}

	private function help($command = null) {
		if(isset($this->args[1]) || isset($command)) {
			if(isset($this->args[1]))
				$command = $this->args[1];
			switch($command) {
				case 'list':
				case 'ls':
					$this->out("Description: List all products");
					$this->out("Usage:\n\tConsole/cake products {list,ls}");
					break;
				case 'add':
					$this->out("Description: Add a product");
					$this->out("Usage:\n\tConsole/cake products add <product's name/title> <price> [image path]");
					break;
				case 'delete':
				case 'remove':
					$this->out("Description: Remove a product");
					$this->out("Usage:\n\tConsole/cake products {remove,delete} <product's ID>");
					break;
				case 'edit':
					$this->out('Description: Update/edit an existing product');
					$this->out("Usage:\n\tConsole/cake products edit <product's ID>");
				default:
					'Command not found';
			}
		} else {
			$this->out("Usage:\n\tConsole/cake products {list,add,remove,delete,edit}");
			$this->out("Products management utility\n");
			$this->out("Known Commands:");
			$this->out("\tlist: List products");
			$this->out("\tls: List products");
			$this->out("\tadd: Add a product");
			$this->out("\tremove: Remove a product");
			$this->out("\tdelete: Remove a product");
			$this->out("\tedit: Edit a product");
			$this->out("Please use the following command to find out command specific usage:");
			$this->out("\tConsole/cake products help <command>");
		}
	}

	private function ls() {
		$products = $this->Product->find('all');
		foreach($products as $product) {
			$this->out("ID: {$product['Product']['id']}\t\"{$product['Product']['title']}\"\t{$product['Product']['price']}");
		}
	}

	private function add() {
		if(count($this->args) >= 3) {
			$title = $this->args[1];
			$price = $this->args[2];
			//Image checks
			if(isset($this->args[3]) && file_exists($this->args[3])) {
				$image = $this->args[3];
				$ext = pathinfo($this->args[3], PATHINFO_EXTENSION);
				if(!isset($ext)) {
					//No extension
					$this->out('Unable to determine file extension. Not copying image.');
					unset($image);
				} else if($ext != 'jpg' && $ext != 'jpeg' && $ext != 'png' && $ext != 'gif') {
					//Not an image
					$this->out('Not an image. Please use only jpg, jpeg, png and gif images. Not copying image.');
					unset($image);
				}
			} else if(isset($this->args[3]) && !file_exists($this->args[3])) {
				$this->out("File {$this->args[3]} not found. Not copying image.");
				unset($image);
			} else {
				$this->out('No image supplied. Not copying image.');
			}

			//Proceed with database operations
			$data = array('Product' => array(
				'title' => $title,
				'price' => $price
			));
			if($this->Product->save($data)) {
				$this->out('Product added to database');
				//Copy image
				if(isset($image) && copy($image, $this->IMAGE_PATH . $title . '.' . $ext)) {
					$this->out('Image copied.');
				}
			}
		} else {
			$this->out('Missing arguments');
			$this->help('add');
		}
	}

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

	private function edit() {}
}
?>
