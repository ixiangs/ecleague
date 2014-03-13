<?php
namespace Toys\Framework;

class Configuration {
	
	const URL_FORAMT_QUERY_STRING = 1;
	const URL_FORMAT_NAME_PARAMETER = 2;
	const URL_FORMAT_SEF = 3;

	public static $domains = array();
	public static $defaultDomain = null;
	public static $urlFormat = Configuration::URL_FORMAT_NAME_PARAMETER;//ex:querystring, nameParameter, noNameParameter
	public static $templateExtensions = array('.php');
	public static $templateDirectories = null;
	public static $templateFunctions = array();
	public static $templateTheme = 'default';
	public static $componentDirectories = array();
	// public static $components = array();
	// public static $language = 'zh-CN';
	public static $trace = false;

	public static function addDomain($name, $namespace, $indexUrl, $indexHandler, $default = false) {//($name, $url, $namespace, $action, $default = false) {
		$d = new Domain($name, $namespace, $indexUrl, $indexHandler, $default);
		self::$domains[$name] = $d;
		if ($default) {
			self::$defaultDomain = $d;
		}
	}

	public static function addTemplateFunction($name, \Closure $func){
		self::$templateFunctions[$name] = $func;
	}
}

Configuration::addTemplateFunction('htmlInput', function($type, $id, $name, $class, $value, array $attrs = array()){
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

Configuration::addTemplateFunction('htmlSelect', function($caption, $items, $id, $name, $class, $value, array $attrs = array()){
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

Configuration::addTemplateFunction('htmlGroupSelect', function($caption, $items, $id, $name, $class, $value, array $attrs = array()){
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

Configuration::addTemplateFunction('htmlCheckboxes', function($items, $name, $class, array $values = array(), array $attrs = array()){
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