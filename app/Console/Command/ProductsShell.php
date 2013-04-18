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
					$this->out('Unable to determine file extension. Not copying image');
					unset($image);
				}
			} else if(!file_exists($this->args[3])) {
				$this->out("File {$this->args[3]} not found. Not copying image.");
				unset($image);
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

	private function remove() {}
	private function edit() {}
}
?>
