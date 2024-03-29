<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	/**
	 * @Override
	 * */
	public $components = array(
		'Session',
		'Auth' => array(
			//Login and logout redirection
			'loginRedirect' => array('controller' => 'products', 'action' => 'index'),
			'logoutRedirect' => array('controller' => 'products', 'action' => 'index'),
			//Redirect to administrators instead of users/login page
			'loginAction' => array(
				'controller' => 'Administrators',
				'action' => 'login'
			),
			//Use controller authorization with isAuthorized() function
			'authorize' => array('Controller'),
			//Set Form authentication model Administrator instead of user
			'authenticate' => array(
				'Form' => array(
					'userModel' => 'Administrator'
				)
			)
		)
	);

	/**
	 * @Override
	 * */
	public function beforeFilter() {
		//No authentication for index and view action for all controllers
		$this->Auth->allow('index', 'view');
	}

	/**
	 * Callback function for authorization
	 * @returns False for unauthorized access
	 * */
	public function isAuthorized($user) {
		//Allow admin with superuser priviledge to access everything
		if(isset($user['superuser']) && $user['superuser'] === 1)
			return true;

		//Default deny
		return false;
	}
}
