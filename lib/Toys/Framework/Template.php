<?php
namespace Toys\Framework;

use Toys\Joy;
use Toys\Util\ArrayUtil, Toys\Util\PathUtil, Toys\Util\StringUtil;

class Template {

	private static $_blocks = array();
	private static $_data = array();
	private static $_currentBlock = NULL;

	protected $applicationContext = null;
	protected $router = null;
	protected $session = null;
	protected $request = null;
	protected $objective = null;

	public function __construct($data = array()) {
		if (is_array($data)) {
			self::$_data = array_merge(self::$_data, $data);
		}
		$this -> applicationContext = Application::singleton() -> getContext();
		$this -> router = $this -> applicationContext -> getRouter();
		$this -> session = $this -> applicationContext -> getSession();
		$this -> request = $this -> applicationContext -> getRequest();
		$this -> objective = $this -> applicationContext -> getObjective();
	}

	public function __get($name) {
		return $this -> get($name);
	}

	public function __call($name, $args) {
		if (array_key_exists($name, Configuration::$templateFunctions)) {
			return call_user_func_array(Configuration::$templateFunctions[$name], $args);
		}
	}

	public function assign() {
		$args = func_get_args();
		$len = func_num_args();
		if ($len == 1 && is_array($args[0])) {
			self::$_data = array_merge(self::$_data, $data);
		} else {
			self::$_data[$args[0]] = $args[1];
		}
		return $this;
	}

	public function get($name, $default = '') {
		$result = NULL;
		if (array_key_exists($name, self::$_data)) {
			$result = self::$_data[$name];
		} elseif ($this -> applicationContext -> hasItem($name)) {
			$result = $this -> applicationContext -> getItem($name);
		}

		if (is_string($result)) {
			$result = urldecode($result);
		}
		return is_null($result) ? $default : $result;
	}

	public function has($name) {
		return array_key_exists($name, self::$_data);
	}

	protected function removeBlock($name = 'default') {
		if ($this -> hasBlock($name)) {
			unset(self::$_blocks[$name]);
		}
		return $this;
	}

	protected function beginBlock($name = 'default') {
		ob_start();
		self::$_currentBlock = $name;
		return $this;
	}

	protected function endBlock() {
		if ($this -> hasBlock(self::$_currentBlock)) {
			$content = self::$_blocks[self::$_currentBlock];
			$content .= ob_get_clean();
			self::$_blocks[self::$_currentBlock] = $content;
		} else {
			self::$_blocks[self::$_currentBlock] = ob_get_clean();
		}
		self::$_currentBlock = NULL;
		return $this;
	}

	protected function nextBlock($name = 'default') {
		$this -> endBlock();
		$this -> beginBlock($name);
		return $this;
	}

	protected function renderBlock($name = 'default') {
		foreach (self::$_blocks as $n => $v) {
			if ($name == $n) {
				return $v;
			}
		}
		return '';
	}

	protected function hasBlock($name) {
		return array_key_exists($name, self::$_blocks);
	}

	protected function includeTemplate($filename, $data = array()) {
		$tmpl = new self($data);
		return $tmpl -> render($filename);
	}

	public function render($path = null) {
		$dirs = Configuration::$templateDirectories;
		$extensions = Configuration::$templateExtensions;
		$theme = Configuration::$templateTheme;
		$lang = $this->request->getBrowserLanguage();
		$objective = $this -> applicationContext -> getObjective();
		$action = $objective -> getAction();
		// $package = StringUtil::PascalCasingToDash($objective->getPackage());
		$component = $objective->getComponent();
		$controller = $objective->getController();
		$domain = strtolower($this -> applicationContext -> getDomain() -> getName());
		$subPaths = array();
		if (empty($path)) {
			$subPaths = array(
				$domain . '/' . $lang . '/' . $theme .'/'. $component .'/' . $controller . '/' . $action, 
				$domain . '/' . $lang .'/'. $component .'/' . $controller . '/' . $action, 
				$domain . '/' . $theme .'/'. $component .'/' . $controller . '/' . $action, 
				$domain .'/'. $component .'/' . $controller . '/' . $action, 
				$component .'/' . $controller . '/' . $action
			);
		} else {
			$subPaths = array(
				$domain . '/' . $lang . '/' . $theme . '/' . $path, 
				$domain . '/' . $lang . '/' . $path, 
				$domain . '/' . $theme . '/' . $path, 
				$domain . '/' . $path,
				$path
			);
		}

		foreach ($dirs as $dir) {
			foreach ($subPaths as $s) {
				foreach ($extensions as $ex) {
					$file = PathUtil::combines($dir, $s, $ex);
					if (file_exists($file)) {
						if (Configuration::$trace) {
							Joy::logger() -> v($file, 'template');
						}
						ob_start();
						include $file;
						return ob_get_clean();
					}
				}
			}
		}

		return null;
	}

}
