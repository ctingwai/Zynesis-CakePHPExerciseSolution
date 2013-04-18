<?php
class CommentsController extends AppController {
	/**
	 * @Override
	 * */
	public function beforeFilter() {
		parent::beforeFilter();
		//$this->Auth->allow('add');
	}

	/**
	 * View comments
	 * */
	public function view() {
		//Do nothing if no arguments
		if(isset($this->passedArgs['pid'])) {
			$pid = $this->passedArgs['pid'];
			$comments = $this->Comment->find('all', array(
				'fields' => array('user', 'comment'),
				'conditions' => array('pid' => $pid)
			));
			
			if($this->request->is('post')) {
				$this->request->data['Comment']['pid'] = $this->passedArgs['pid'];
				if($this->Comment->save($this->request->data)) {
					$this->Session->setFlash('Comment saved ');
					$this->redirect($this->request->referer());
				} else {
					$this->Session->setFlash('An unknown error has occured. Please try again later.');
				}
			}

			if($this->request->is('requested')) {
				return $comments;
			} else {
				$this->set('comments', $comments);
			}
		} else {
			$this->redirect('/');
		}
	}
}
?>
