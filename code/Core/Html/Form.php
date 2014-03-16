<?php
namespace Html;
use Toy\Util\StringUtil;

class Form{
	
	public function renderBegin($id='form1', $method='post'){
		$attrs = array("id=$id", "method=$method", 'role="form"');
		return '<form '.implode(' ', $attrs).'>';
	}
	
	public function renderInput($type, $label, $id, $name, $value, $attrs = array()){
		$attrs['type'] = $type;
		$attrs['id'] = $id;
		$attrs['name'] = $name;
		$attrs['value'] = $value;
		$attrs['class'] = array_key_exists('class', $attrs)? 'form-control'.' '.$attrs['class']: 'form-control';
		$arr = array();
		foreach($attrs as $k=>$v){
			$arr[] = "$k=\"$v\"";
		}
				
	  $html = array('<div class="form-group">');
	  $html[] = '<label for="'.$id.'">'.$label.'</label>';
		$html[] = '<input '.implode(' ', $arr).'/>';
		$html[] = '</div>';
		
		return implode('', $html);
	}
	
	public function renderSelect($label, $caption, $items, $id, $name, $value, $attrs = array()){
		$attrs['id'] = $id;
		$attrs['name'] = $name;
		$attrs['class'] = $class;
		$arr = array();
		foreach($attrs as $k=>$v){
			$arr[] = "$k=\"$v\"";
		}	
	  $html = array('<div class="form-group">');
	  $html[] = '<label for="'.$id.'">'.$label.'</label>';		
		$html[] = '<select '.implode(' ', $arr).'/>';
		if(!empty($caption)){
			if(is_string($caption)){
				$html[] = '<option value="">'.$caption.'</option>';
			}elseif(is_array($caption)){
				$ks = array_keys($caption);
				$vs = array_values($caption);
				$html[] = '<option value="'.$ks[0].'">'.$vs[0].'</option>';
			}
		}
		
		foreach($items as $key=>$item){
			if(is_array($item) && array_key_exists('options', $item)){
				$html[] = '<optgroup label="'.$item['label'].'">';
				foreach($item['options'] as $option=>$text){
					if($value == $option){
						$html[] = "<option value=\"$option\" selected>$text</option>";
					}else{
						$html[] = "<option value=\"$option\">$text</option>";
					}
				}
				$html[] = '</optgroup>';
			}else{
				if($value == $key){
					$html[] = "<option value=\"$key\" selected>$item</option>";
				}else{
					$html[] = "<option value=\"$key\">$item</option>";
				}				
			}
		}
		$html[] = '</select>';
		$html[] = '</div>';
		
		return implode('', $html);
	}	
	
	public function renderEnd(){
		return '</form>';
	}
}
