<?php
/**
 * Application level View Helper
 *
 * This file is application-wide helper file. You can put all
 * application-wide helper-related methods here.
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
 * @package       app.View.Helper
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Helper', 'View');

/**
 * Application helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
App::uses('Folder', 'Utility');
class AppHelper extends Helper {
	/**
	 * Get product image path
	 * @param title Product title
	 * @return Image path of the product
	 * */
	public function getImgPath($title) {
		//$img = glob('../product_img/' . $title . '\.{jpg|jpeg|gif|png}', GLOB_BRACE);
		$imgFolder = new Folder('product_img/');
		$img = $imgFolder->find($title . '\.(jpg|gif|jpeg|png)');

		if(!empty($img)) {
			return '../product_img/' . $img[0];
		}

		return '../product_img/default_no_image_found.png';
	}
}
