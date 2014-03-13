<?php
$errMsgs = $this->session->pop('errors');
if(!empty($errMsgs)){
	if(is_array($errMsgs)){
		$html[] = array();
		foreach($errMsgs as $msg){
			$html[] = "<div class=\"alert alert-danger\">$msg</div>";
		}
		echo implode('', $html);
	}else{
		echo "<div class=\"alert alert-danger\">$errMsgs</div>";
	}
}
$successMsgs = $this->session->pop('success');
if(!empty($successMsgs)){
	if(is_array($successMsgs)){
		$html[] = array();
		foreach($successMsgs as $msg){
			$html[] = "<div class=\"alert alert-success\">$msg</div>";
		}
		echo implode('', $html);
	}else{
		echo "<div class=\"alert alert-success\">$successMsgs</div>";
	}
}
