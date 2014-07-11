<?php 

$time = microtime_float();
echo $time;echo '<br>';
echo microtime();

/** 获取当前时间戳，精确到毫秒 */

function microtime_float()
{
	list($usec, $sec) = explode(" ", microtime());
	return ((float)$usec + (float)$sec);
}


/** 格式化时间戳，精确到毫秒，x代表毫秒 */

function microtime_format($tag, $time)
{
	list($usec, $sec) = explode(".", $time);
	$date = date($tag,$usec);
	return str_replace('x', $sec, $date);
}

?>