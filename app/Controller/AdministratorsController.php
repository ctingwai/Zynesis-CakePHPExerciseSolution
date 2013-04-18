<?php
class AdministratorsController extends AppController {
	private $emptyRecords = false;

	/**
	 * @Override
	 * */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('logout', 'login');
	}

	/**
	 * Index page
	 * */
	public function index() {
		$this->redirect(array('action' => 'login'));
	}

	/**
	 * Administrator login
	 * */
	public function login() {
		//Login an administration
		if($this->request->is('post')) {
			if($this->Auth->login()) {
				$this->redirect($this->Auth->redirectUrl());
			}else{
				$this->Session->setFlash(__('Invalid Username/Password. Please try again.'));
			}
		}
	}

	/**
	 * Administrator logout
	 * */
	public function logout() {
		$this->redirect($this->Auth->logout());
	}
}
?>
