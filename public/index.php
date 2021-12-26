<?php
	if(isset($_GET['url'])){
        $url = rtrim($_GET['url'], '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);
        if(is_null($url)){
        	require_once 'error/index.php';exit;
        }
        else{
        	require_once '../app/config/routes.php';
        	require_once '../app/require.php';
        }
    } else {
        require_once 'error/index.php';exit;
    }
?>
