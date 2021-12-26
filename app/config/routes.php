<?php
$url = rtrim($_GET['url'], '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);
function writeLog($api_url, $api_req, $api_res, $log_path) {
    /*Write action to txt log*/
    $log  = "Date & Time: ".date("Y-m-d H:i:s").PHP_EOL.
            "URL: ".$api_url.PHP_EOL.
            "Request: ".$api_req.PHP_EOL.
            "Response: ".$api_res.PHP_EOL.
            "-------------------------".PHP_EOL;
    $log_filename = getcwd().$log_path.date("Y-m-d").'.txt';        
    file_put_contents($log_filename, $log, FILE_APPEND);
}

writeLog($_GET['url'], "GET :- ".json_encode($_GET)." POST :- ".json_encode($_POST), '', '/log/requestLogs/log_');

if(!in_array($_GET['url'], array('master','master/searchBookByAuthor','DisplayController/getBookDetails'))){
	require_once('../public/error/index.php');exit;
}

if($_GET['url'] == 'master/searchBookByAuthor')
	$_GET['url'] = 'DisplayController/searchBookByAuthor';
?>

