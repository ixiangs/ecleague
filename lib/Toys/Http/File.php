<?php
namespace Toys\Http;

class File {

	//    private static $_mimeType = array(
	//        'jpg' => 'image/jpeg', 'gif' => 'image/gif', 'png' => 'image/png',
	//        'tif' => 'image/tiff', 'tiff' => 'image/tiff', 'ico' => 'image/x-icon',
	//        'swf' => 'application/x-shockwave-flash', 'pdf' => 'application/pdf',
	//        'zip' => 'application/zip', 'gz' => 'application/x-gzip', 'tar' => 'application/x-tar',
	//        'bz' => 'application/x-bzip', 'bz2' => 'application/x-bzip2', 'txt' => 'text/plain',
	//        'asc' => 'text/plain', 'htm' => 'text/html', 'html' => 'text/html',
	//        'css' => 'text/css', 'js' => 'text/javascript', 'xml' => 'text/xml',
	//        'xsl' => 'application/xsl+xml', 'ogg' => 'application/ogg', 'mp3' => 'audio/mpeg',
	//        'wav' => 'audio/x-wav', 'avi' => 'video/x-msvideo', 'mpg' => 'video/mpeg',
	//        'mpeg' => 'video/mpeg', 'mov' => 'video/quicktime', 'flv' => 'video/x-flv',
	//        'php' => 'text/x-php'
	//    );

	private $_fileName = '';
	private $_extension = '';
	private $_type = '';
	private $_tmpName = '';
	private $_size = 0;
	private $_width = 0;
	private $_height = 0;
	private $_isImage = false;
	private $_errorCode = UPLOAD_ERR_OK;

	public function __construct($file) {
		$names = explode('.', $file['name']);
		$this -> _fileName = $names[0];
		$this -> _extension = $names[1];
		$this -> _type = $file['type'];
		$this -> _tmpName = $file['tmp_name'];
		$this -> _size = $file['size'];
		$this -> __errorCode = $file['error'];

		if ($this -> checkExtension(array("jpg", "jpeg", "gif", "png"))) {
			$arr = @getimagesize($this -> _tmpName);
			if ($arr !== false) {
				$this -> _width = $arr[0];
				$this -> _height = $arr[1];
			}
		}
	}

	public function getError() {
		return $this -> _errorCode;
	}

	public function isOk() {
		return $this -> _errorCode == UPLOAD_ERR_OK;
	}

	public function getFileName() {
		return $this -> _fileName;
	}

	public function getTmpName() {
		return $this -> _tmpName;
	}

	public function getExtension() {
		return $this -> _extension;
	}

	public function getWidth() {
		return $this -> _width;
	}

	public function getHeight() {
		return $this -> _height;
	}

	public function getSize() {
		return $this -> _size;
	}

	public function checkExtension($exs) {
		return in_array($this -> _extension, $exs);
	}

	public function isImage() {
		return $this -> _isImage;
	}

	public function getMd5Code() {
		return md5_file($this -> _tmpName);
	}

	public function getMd5Name() {
		return md5_file($this -> _tmpName) . '.' . $this -> _extension;
	}

}
