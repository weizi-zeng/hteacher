<?php 
define('IN_ECS', true);
define('ROOT_PATH', preg_replace('/includes(.*)/i', '', str_replace('\\', '/', __FILE__)));

$allow_file_types = '|GIF|JPG|PNG|BMP|SWF|DOC|XLS|PPT|MID|WAV|ZIP|RAR|PDF|CHM|RM|TXT|XLSX|CSV|';

if ($allow_file_types && stristr($allow_file_types, '|' . $format . '|') === false)
{
	echo "3";echo '<br>';
	$format = '';
}


if (!check_file_type("D:\phpserver\phpfileuploadtmp\phpB989.tmp", "studentTemplate.csv", $allow_file_types))
{
	print_r(array("error"=>1,"msg"=>"您上传的文件格式不被允许,只能是以下格式的文件才能上传:"));
}

print_r("over");



?>

