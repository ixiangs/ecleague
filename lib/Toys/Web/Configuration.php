<?php
namespace Toys\Web;

class Configuration {

	public static $domains = array();
    public static $seoUrl = true;
    public static $seoParameter = true;
	public static $templateExtensions = array('.php');
	public static $templateDirectories = null;
	public static $templateFunctions = array();
	public static $templateTheme = 'default';
	public static $componentDirectories = array();
    public static $logger = null;
	public static $trace = false;

	public static function addDomain($name, $namespace, $startUrl, $default = false) {
		$d = new Domain($name, $namespace, $startUrl, $default);
		self::$domains[$name] = $d;
//		if ($default) {
//			self::$defaultDomain = $d;
//		}
	}

//	public static function addTemplateFunction($name, \Closure $func){
//		self::$templateFunctions[$name] = $func;
//	}
}

//Configuration::addTemplateFunction('htmlInput', function($type, $id, $name, $class, $value, array $attrs = array()){
//	$attrs['type'] = $type;
//	$attrs['id'] = $id;
//	$attrs['name'] = $name;
//	$attrs['value'] = $value;
//	$attrs['class'] = $class;
//	$arr = array();
//	foreach($attrs as $k=>$v){
//		$arr[] = "$k=\"$v\"";
//	}
//	return '<input '.implode(' ', $arr).'/>';
//});
//
//Configuration::addTemplateFunction('htmlSelect', function($caption, $items, $id, $name, $class, $value, array $attrs = array()){
//	$attrs['id'] = $id;
//	$attrs['name'] = $name;
//	$attrs['class'] = $class;
//	$arr = array();
//	foreach($attrs as $k=>$v){
//		$arr[] = "$k=\"$v\"";
//	}
//	$html = array('<select '.implode(' ', $arr).'/>');
//	if(!empty($caption)){
//		if(is_string($caption)){
//			$html[] = '<option value="">'.$caption.'</option>';
//		}elseif(is_array($caption)){
//			$ks = array_keys($caption);
//			$vs = array_values($caption);
//			$html[] = '<option value="'.$ks[0].'">'.$vs[0].'</option>';
//		}
//	}
//
//	foreach($items as $option=>$text){
//		if($value == $option){
//			$html[] = "<option value=\"$option\" selected>$text</option>";
//		}else{
//			$html[] = "<option value=\"$option\">$text</option>";
//		}
//	}
//	$html[] = '</select>';
//	return implode('', $html);
//});
//
//Configuration::addTemplateFunction('htmlGroupSelect', function($caption, $items, $id, $name, $class, $value, array $attrs = array()){
//	$attrs['id'] = $id;
//	$attrs['name'] = $name;
//	$attrs['class'] = $class;
//	$arr = array();
//	foreach($attrs as $k=>$v){
//		$arr[] = "$k=\"$v\"";
//	}
//	$html = array('<select '.implode(' ', $arr).'/>');
//	if(!empty($caption)){
//		if(is_string($caption)){
//			$html[] = '<option value="">'.$caption.'</option>';
//		}elseif(is_array($caption)){
//			$ks = array_keys($caption);
//			$vs = array_values($caption);
//			$html[] = '<option value="'.$ks[0].'">'.$vs[0].'</option>';
//		}
//	}
//
//	foreach($items as $item){
//		$html[] = '<optgroup label="'.$item['label'].'">';
//		foreach($item['options'] as $option=>$text){
//			if($value == $option){
//				$html[] = "<option value=\"$option\" selected>$text</option>";
//			}else{
//				$html[] = "<option value=\"$option\">$text</option>";
//			}
//		}
//		$html[] = '</optgroup>';
//	}
//	$html[] = '</select>';
//	return implode('', $html);
//});
//
//Configuration::addTemplateFunction('htmlCheckboxes', function($items, $name, $class, array $values = array(), array $attrs = array()){
//	$html = array();
//	foreach($items as $option=>$text){
//		if(in_array($option, $values)){
//			$html[] = "<label class=\"checkbox-inline\"><input type=\"checkbox\" name=\"$name\" value=\"$option\" checked=\"true\">$text</label>";
//		}else{
//			$html[] = "<label class=\"checkbox-inline\"><input type=\"checkbox\" name=\"$name\" value=\"$option\">$text</label>";
//		}
//	}
//	$html[] = '</select>';
//	return implode('', $html);
//});