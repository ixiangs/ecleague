<?php
$pc = ceil($this->total / PAGINATION_SIZE);
$start = 0;
$end = 0;
if($pc < PAGINATION_RANGE){
	$start = 1;
	$end = $pc;
} else if($this->pageIndex == $pc){
	$start = $pc - PAGINATION_RANGE - 1;
	$end = $pc;
} else {
	$pr = floor($this->pageIndex / PAGINATION_RANGE);
	$start = $pr <= 0? 1: $pr*PAGINATION_RANGE - 1;
	$end = $start + PAGINATION_RANGE + 1;
	if($end > $pc) {
		$start = $pc - PAGINATION_RANGE - 1;
		$end = $pc;
	}
	if($start < 1){
		$start = 1;
		$end = $start + PAGINATION_RANGE;
	}
}

$pargs = $this->applicationContext->getRequest()->getAllParameters();
$pargs['pageindex'] = 1;
$html = array(
	"<ul class=\"pagination pagination-lg\">",
);
if($this->pageIndex > PAGINATION_RANGE) {
	$html[] = sprintf("<li><a href=\"%s\">&larr; %s</a></li>", $this->router->buildUrl().'?'.http_build_query($pargs) , 1);
}

for($i = $start; $i <= $end; $i++){
	if($i == $this->pageIndex) {
		$html[] = sprintf("<li class=\"active\"><span>%s</span></li>", $i);
	} else {
		$pargs['pageindex'] = $i;
		$html[] = sprintf("<li><a href=\"%s\">%s</a></li>", $this->router->buildUrl().'?'.http_build_query($pargs), $i);
	}
}

if($this->pageIndex < $pc-PAGINATION_RANGE) {
	$pargs['pageindex'] = $pc;
	$html[] = sprintf("<li><a href=\"%s\">%s &rarr;</a></li>", $this->router->buildUrl().'?'.http_build_query($pargs), $pc);
}
$html[] = '</url>';
echo implode('', $html);