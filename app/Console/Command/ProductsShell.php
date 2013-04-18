<?php
class ProductsShell extends AppShell {
	var $uses = array('Product');

	public function main() {
		//App::import('Component');

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
			case 'edit':
				$this->edit();
			default:
				$this->out('Unknown command');
				$this->help();
		}


	}

	private function help() {
		$this->out("Usage:\n\tConsole/cake products [list|add|remove|delete|edit]");
		$this->out("Products management utility\n");
		$this->out("Known Commands:");
		$this->out("\tlist: List products");
		$this->out("\tadd: Add a product");
		$this->out("\tremove: Remove a product");
		$this->out("\tdelete: Remove a product");
		$this->out("\tedit: Edit a product");
	}

	private function ls() {
		$products = $this->Product->find('all');
		foreach($products as $product) {
			$this->out("ID: {$product['Product']['id']}\t\"{$product['Product']['title']}\"\t{$product['Product']['price']}");
		}
	}

	private function add() {}
	private function remove() {}
	private function edit() {}


}
?>
