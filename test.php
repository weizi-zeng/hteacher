<?php 

define('IN_ECS',true);


echo get_prefix("asdfaasd*asdfase.xt");

function get_prefix($filename){
	$str = explode(".", $filename);
	if(count($str)>1){
		return $str[count($str)-1];
	}
	return "uk";
}

?>