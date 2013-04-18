<?php
class AdminShell extends AppShell {
	var $uses = array('Administrator');

	public function main() {
		App::import('Component', 'Auth', 'Security');

		switch($this->command) {
			case 'add':
				$this->add();
				break;
			case 'remove':
			case 'delete':
				$this->remove();
				break;
			case 'passwd':
				$this->passwd();
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
	 * Print help for this utility
	 * */
	private function help() {
		$this->out("Usage:\n\tConsole/cake admin [add|remove|delete|passwd]\n");
		$this->out("Administrator management utility\n");
		$this->out("Known Commands:");
		$this->out("\tadd: Add an administrator");
		$this->out("\tremove: Remove an administrator");
		$this->out("\tdelete: Remove an administrator");
		$this->out("\tpasswd: Change password for an administrator");
	}

	/**
	 * Change password for an admin
	 * */
	private function passwd() {
		$username = mysql_real_escape_string($this->in('Username: '));

		$id = $this->Administrator->find('first', array(
			'conditions' => array('username' => $username),
			'fields' => array('id')
		));

		if(isset($id['Administrator']['id'])) {
			$id = $id['Administrator']['id'];

			while(empty($new)) {
				$new = mysql_real_escape_string($this->in('New Password: '));
				if(empty($new))
					$this->out('Password cannot be empty!');
			}

			$confirm = mysql_real_escape_string($this->in('Confirm Password: '));
			if($new === $confirm) {
				$hash = mysql_real_escape_string(Security::hash($new, null, Configure::read('Security.salt')));
				$query = "UPDATE administrators SET `password`='$hash' WHERE `id`='$id'";
				$this->Administrator->query($query);
			}
		} else {
			$this->out("Administrator '$username' not found.");
		}
	}

	/**
	 * Remove an admin
	 * */
	private function remove() {
		$username = mysql_real_escape_string($this->in('Username to remove: '));
		$id = $this->Administrator->find('first', array(
			'conditions' => array('username' => $username),
			'fields' => array('id')
		));
		if(isset($id['Administrator']['id'])) {
			$id = $id['Administrator']['id'];
			if($this->Administrator->delete($id))
				$this->out("Administrator '$username' deleted.");
			else
				$this->out("Unable to delete '$username'...");
		} else {
			$this->out("Administrator '$username' not found.");
		}
	}

	/**
	 * Add an admin
	 * */
	private function add() {
		while(empty($username)) {
			$username = mysql_real_escape_string($this->in('Username: '));
			if(empty($username))
				$this->out('Username must not be empty!');
		}

		while(empty($password)) {
			$password = $this->in('Password: ');
			if(empty($password))
				$this->out('Password must not be empty');
		}

		$passwordConfirm = $this->in('Confirm Password: ');

		if($passwordConfirm === $password) {
			$hash = mysql_real_escape_string(Security::hash($password, null, Configure::read('Security.salt')));
			//Using manual SQL query as CakePHP save() function changes hash values
			$this->Administrator->create();
			$query = "INSERT INTO `administrators` (`username`, `password`) VALUES ('$username', '$hash')";
			$this->Administrator->query($query);
		} else {
			$this->out('Password and confirmation do not match!');
		}
	
	}
}
?>
