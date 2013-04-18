<?php
App::uses('Component', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
class ImageComponent extends Component {
	/**
	 * PHP file upload errors, based on http://php.net/manual/en/features.file-upload.errors.php
	 * */
	private $FILE_ERROR = array(
		1 => 'Uploaded file exceeds maximum allowable file size',
		2 => 'Uploaded file exceeds maximum allowable file size',
		3 => 'File was only partially uploaded',
		4 => 'No file was uploaded',
		6 => 'Missing temporary folder',
		7 => 'Failed to write file to disk',
		8 => 'A PHP extension stopped the file upload'
	);

	/**
	 * Check whether the filetype is an image
	 * @param filetype The filetype of the uploaded file
	 * @param error The error code by PHP file upload
	 * @return True if the file is uploaded and is an image
	 * */
	public function isImage($filetype, $error) {
		if($error == 0
			&& ($filetype == 'image/gif'
			|| $filetype == 'image/jpeg'
			|| $filetype == 'image/jpg'
			|| $filetype == 'image/png'))

			return true;
		else
			return false;
	}

	/**
	 * Look up error and process error message
	 * @param filetype The file type of the uploaded file
	 * @param error The error code of the uploaded file
	 * @param maxSize The maximum allowable size of the image
	 * @return Null if the uploaded file is successful or nofile is uploaded, an error message if error is found
	 * */
	public function uploadError($filetype, $error, $maxSize) {
		if(($error == 0 && $this->isImage($filetype, $error)) || $error == 4)
			return null;
		else if($error != 4 && $error != 0)
			return $this->FILE_ERROR[$error] . '.<br />Maximum file size: ' . $maxSize . 'MB';
		else if(!$this->isImage($filetype, $error))
			return 'Not an image.<br />Supported image type: png, jpg, jpeg, gif';
		else
			return 'An unknown error has occured';
	}

	/**
	 * Get file extension
	 * @param filetype The file type of the uploaded file
	 * @return Extension of the given filetype, null if not an image
	 * */
	public function getExtension($filetype) {
		switch($filetype) {
			case 'image/gif':
				return '.gif';
				break;
			case 'image/png':
				return '.png';
				break;
			case 'image/jpg':
				return '.jpg';
				break;
			case 'image/jpeg':
				return '.jpeg';
				break;
			default:
				return null;
		}
	}

	/**
	 * Checks whether the file is uploaded
	 * @param error The error code of the uploaded file
	 * @return True if the file is uploaded, false otherwise
	 * */
	public function isUploaded($error) {
		if($error == 0)
			return true;
		else
			return false;
	}

	/**
	 * Remove a product image
	 * */
	public function rm($title) {
		$imgFolder = new Folder('product_img');
		$images = $imgFolder->find($title . '\.(png|gif|jpg|jpeg)');

		if(!empty($images)) {
			$image = new File($imgFolder->pwd() . '/' . $images[0]);
			$image->delete();
		}
	}
}
?>
