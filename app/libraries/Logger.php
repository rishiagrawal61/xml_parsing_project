<?php
class Logger{
	public function __construct(){
		/*Logger Instantiated*/
	}

    /*Used to save other kinds of log like request log and application logs.*/
	public function writeLog($api_url, $api_req, $api_res, $log_path) {
        /*Write action to txt log*/
        $log  = "Date & Time: ".date("Y-m-d H:i:s").PHP_EOL.
                "URL: ".$api_url.PHP_EOL.
                "Request: ".$api_req.PHP_EOL.
                "Response: ".$api_res.PHP_EOL.
                "-------------------------".PHP_EOL;
        $log_filename = getcwd().$log_path.date("Y-m-d").'.txt';        
        file_put_contents($log_filename, $log, FILE_APPEND);
    }

    /* Used to write the DB Query Log */
    public function writeDBQueryLog($query, $log_path) {
            /*Write action to txt log*/
            $log  = "Date & Time: ".date("Y-m-d H:i:s").PHP_EOL.
                    "Query: ".$query.PHP_EOL.
                    "-------------------------".PHP_EOL;

            $log_filename = getcwd().$log_path.date("Y-m-d").'.txt';        
            file_put_contents($log_filename, $log, FILE_APPEND);
        }
}
?>