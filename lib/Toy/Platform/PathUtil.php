<?php
namespace Toy\Platform;

class PathUtil {

	static public function combines() {
		$result = '';
		$args = func_get_args();
		foreach ($args as $v) {
			if ($v[0] == '.') {
				if(DIRECTORY_SEPARATOR == $result[strlen($result) - 1]){
					$result = substr($result, 0, strlen($result) - 1);
				}
				$result .= $v;				
			}elseif(strpos($v, '.') !== false){
				$result .= $v; 
			}else {
				$result .= DIRECTORY_SEPARATOR == $v[strlen($v) - 1] ? $v : $v . DIRECTORY_SEPARATOR;
			}
		}
		return $result;
	}

	static public function scanCurrent($dir, \Closure $callback) {
		if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != '.' && $file != '..') {
					$filename = PathUtil::combines($dir, $file);
					$callback($filename, pathinfo($filename));
				}
			}
			closedir($handle);
		}
	}

	static public function scanRecursive($dir, \Closure $callback) {
		if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != '.' && $file != '..') {
					$filename = PathUtil::combines($dir, $file);
					if (is_dir($filename)) {
						self::scanRecursive($filename, $callback);
					} else {
						if ($callback($filename, pathinfo($filename))) {
							$result[] = $filename;
						}
					}
				}
			}
			closedir($handle);
		}
	}
}
