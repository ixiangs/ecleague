<?php
\Toys\Framework\Configuration::addTemplateFunction('deleteConfirm', function($url){
	$msg = \Toys\Joy::languages()->get('delete_confirm');
	return "javascript:if(confirm('".$msg."')) window.location='$url'";
});

\Toys\Framework\Configuration::addTemplateFunction('formField', function($for, $label, $input){
  $html = array('<div class="form-group">');
  $html[] = '<label for="'.$for.'">'.$label.'</label>';
	$html[] = $input;
	$html[] = '</div>';
	return implode('', $html);
});
