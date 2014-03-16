<?php
namespace Toy\Web;

use Toy\Platform\PathUtil;

class Template
{

    private static $_blocks = array();
    private static $_data = array();
    private static $_currentBlock = NULL;

    protected $applicationContext = null;
    protected $router = null;
    protected $session = null;
    protected $request = null;

    public function __construct($data = array())
    {
        if (is_array($data)) {
            self::$_data = array_merge(self::$_data, $data);
        }
        $this->applicationContext = Application::singleton()->getContext();
        $this->router = $this->applicationContext->router;
        $this->session = $this->applicationContext->session;
        $this->request = $this->applicationContext->request;
    }

    public function __get($name)
    {
        return $this->get($name);
    }

	public function __call($name, $args) {
		if (array_key_exists($name, self::$_functions)) {
			return call_user_func_array(self::$_functions[$name], $args);
		}
	}

    public function assign()
    {
        $args = func_get_args();
        $len = func_num_args();
        if ($len == 1 && is_array($args[0])) {
            self::$_data = array_merge(self::$_data, $args);
        } else {
            self::$_data[$args[0]] = $args[1];
        }
        return $this;
    }

    public function get($name, $default = '')
    {
        $result = NULL;
        if (array_key_exists($name, self::$_data)) {
            $result = self::$_data[$name];
        } elseif (array_key_exists($name, $this->applicationContext->items)) {
            $result = $this->applicationContext->items($name);
        }

        if (is_string($result)) {
            $result = urldecode($result);
        }
        return is_null($result) ? $default : $result;
    }

    public function has($name)
    {
        return array_key_exists($name, self::$_data);
    }

    protected function removeBlock($name = 'default')
    {
        if ($this->hasBlock($name)) {
            unset(self::$_blocks[$name]);
        }
        return $this;
    }

    protected function beginBlock($name = 'default')
    {
        ob_start();
        self::$_currentBlock = $name;
        return $this;
    }

    protected function endBlock()
    {
        if ($this->hasBlock(self::$_currentBlock)) {
            $content = self::$_blocks[self::$_currentBlock];
            $content .= ob_get_clean();
            self::$_blocks[self::$_currentBlock] = $content;
        } else {
            self::$_blocks[self::$_currentBlock] = ob_get_clean();
        }
        self::$_currentBlock = NULL;
        return $this;
    }

    protected function nextBlock($name = 'default')
    {
        $this->endBlock();
        $this->beginBlock($name);
        return $this;
    }

    protected function renderBlock($name = 'default')
    {
        foreach (self::$_blocks as $n => $v) {
            if ($name == $n) {
                return $v;
            }
        }
        return '';
    }

    protected function hasBlock($name)
    {
        return array_key_exists($name, self::$_blocks);
    }

    protected function includeTemplate($filename, $data = array())
    {
        $tmpl = new self($data);
        return $tmpl->render($filename);
    }

    public function render($path = null)
    {
        $dirs = Configuration::$templateDirectories;
        $extensions = Configuration::$templateExtensions;
        $theme = Configuration::$templateTheme;
        $router = $this->applicationContext->router;
        $lang = 'zh-cn';//$this->request->getBrowserLanguage();
//        $objective = $this->applicationContext->getObjective();
        $action = $router->action;
        // $package = StringUtil::PascalCasingToDash($objective->getPackage());
        $component = $router->component;
        $controller = $router->controller;
        $domain = strtolower($router->domain->getName());
//        $subPaths = array();
        if (empty($path)) {
            $subPaths = array(
                $domain . '/' . $lang . '/' . $theme . '/' . $component . '/' . $controller . '/' . $action,
                $domain . '/' . $lang . '/' . $component . '/' . $controller . '/' . $action,
                $domain . '/' . $theme . '/' . $component . '/' . $controller . '/' . $action,
                $domain . '/' . $component . '/' . $controller . '/' . $action,
                $component . '/' . $controller . '/' . $action
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
                            Configuration::$logger->v($file, 'template');
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

    private static $_functions = array();
    public static function addFunction($name, $func){
        self::$_functions[$name] = $func;
    }
}

Template::addFunction('htmlInput', function($type, $id, $name, $class, $value, array $attrs = array()){
	$attrs['type'] = $type;
	$attrs['id'] = $id;
	$attrs['name'] = $name;
	$attrs['value'] = $value;
	$attrs['class'] = $class;
	$arr = array();
	foreach($attrs as $k=>$v){
		$arr[] = "$k=\"$v\"";
	}
	return '<input '.implode(' ', $arr).'/>';
});

Template::addFunction('htmlSelect', function($caption, $items, $id, $name, $class, $value, array $attrs = array()){
	$attrs['id'] = $id;
	$attrs['name'] = $name;
	$attrs['class'] = $class;
	$arr = array();
	foreach($attrs as $k=>$v){
		$arr[] = "$k=\"$v\"";
	}
	$html = array('<select '.implode(' ', $arr).'/>');
	if(!empty($caption)){
		if(is_string($caption)){
			$html[] = '<option value="">'.$caption.'</option>';
		}elseif(is_array($caption)){
			$ks = array_keys($caption);
			$vs = array_values($caption);
			$html[] = '<option value="'.$ks[0].'">'.$vs[0].'</option>';
		}
	}

	foreach($items as $option=>$text){
		if($value == $option){
			$html[] = "<option value=\"$option\" selected>$text</option>";
		}else{
			$html[] = "<option value=\"$option\">$text</option>";
		}
	}
	$html[] = '</select>';
	return implode('', $html);
});

Template::addFunction('htmlGroupSelect', function($caption, $items, $id, $name, $class, $value, array $attrs = array()){
	$attrs['id'] = $id;
	$attrs['name'] = $name;
	$attrs['class'] = $class;
	$arr = array();
	foreach($attrs as $k=>$v){
		$arr[] = "$k=\"$v\"";
	}
	$html = array('<select '.implode(' ', $arr).'/>');
	if(!empty($caption)){
		if(is_string($caption)){
			$html[] = '<option value="">'.$caption.'</option>';
		}elseif(is_array($caption)){
			$ks = array_keys($caption);
			$vs = array_values($caption);
			$html[] = '<option value="'.$ks[0].'">'.$vs[0].'</option>';
		}
	}

	foreach($items as $item){
		$html[] = '<optgroup label="'.$item['label'].'">';
		foreach($item['options'] as $option=>$text){
			if($value == $option){
				$html[] = "<option value=\"$option\" selected>$text</option>";
			}else{
				$html[] = "<option value=\"$option\">$text</option>";
			}
		}
		$html[] = '</optgroup>';
	}
	$html[] = '</select>';
	return implode('', $html);
});

Template::addFunction('htmlCheckboxes', function($items, $name, $class, array $values = array(), array $attrs = array()){
	$html = array();
	foreach($items as $option=>$text){
		if(in_array($option, $values)){
			$html[] = "<label class=\"checkbox-inline\"><input type=\"checkbox\" name=\"$name\" value=\"$option\" checked=\"true\">$text</label>";
		}else{
			$html[] = "<label class=\"checkbox-inline\"><input type=\"checkbox\" name=\"$name\" value=\"$option\">$text</label>";
		}
	}
	$html[] = '</select>';
	return implode('', $html);
});
