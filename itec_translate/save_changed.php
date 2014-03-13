<?php
// header('Content-Type: text/html; charset=utf-8');
$file = $_REQUEST['p'].".csv";
$lines = array();
if (($handle = fopen($file, "r")) !== FALSE) {
  while (($data = fgetcsv($handle)) !== FALSE) {
  	$lines[$data[0]] = $data;
  }
  fclose($handle);
}
if($_SERVER['REQUEST_METHOD'] == 'GET'){
	$json = array();
	foreach($lines as $line){
		$json[] = array(
			'formid'=>$line[1],
			'inputid'=>$line[2],
			'source'=>$line[3],
			'modified'=>$line[4]
		);
	}
	echo json_encode($json);
}else{
	$k = $_POST['p'].'@'.str_replace(' ', '_',$_POST['s']);
	$lines[$k] = array(
		$k, $_POST['f'], $_POST['i'], $_POST['s'], $_POST['m']
	);
	$fp = fopen($file, 'w');
	
	foreach ($lines as $line) {
  	fputcsv($fp, $line);
	}
	
	fclose($fp);
}
