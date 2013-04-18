<?php
class Administrator extends AppModel {
	//Validation rules
	public $validate = array(
		'username' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'Username is required'
			)
		),

		'password' => array(
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'Password is required'
			)
		)
	);

	/**
	 * @Override
	 * */
	public function beforeSave($options = array()) {
		//Password hashing
		if(isset($this->data[$this->alias]['password'])) {
			$this->data[$this->alias]['password']
				= AuthComponent::password($this->data[$this->alias]['password']);
		}

		return true;
	}
}
?>
