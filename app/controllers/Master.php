<?php
include_once('../public/data.php');
class Master extends Controller {
    public function __construct() {
    	ini_set('display_errors', false);
        $this->dataModel = $this->model('DataModel');
        /*Logger Instantiation.*/
        $this->log = new Logger();
    }

    public function index() {
    	try{
	        if(file_exists('../public/data.php')){
	        	$content = getData();
	        	if(count($content) <> 0){
	        		/*Sanitasing the Input data from file with the help of inbuilt PHP functions.*/
	        		$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
	        		$parsedData = $this->readContent($content);
	        		/*If some issues with data being parsed.*/
					if($parsedData['status'] == 0){
						$data = $parsedData['message']; 
						$this->log->writeLog('/index', '', json_encode($data), '/log/log_');
						$this->view('/error/error_page', $data);
					}
					$tableString = '';
					/*If length of parsed data if more than 0 then only we will proceed further.*/
					if(count($parsedData['data']) > 0){
						/*Inserting author and books data to DB.*/
						$processedResult = $this->dataModel->insertIntoDB($parsedData['data']);
						/*If data is inserted successfully we will proceed to view otherwise we will report an error.*/
						if($processedResult['status'] == 0){
							$data = $processedResult['message'];
							$this->log->writeLog('/index', '', json_encode($data), '/log/log_');
							$this->view('/error/error_page', $data);
						} else{
							$data['title'] = 'Uploading and Sanitizing Data';
							$data['authorsInserted'] = $processedResult['author_inserted'];
							$data['namesInserted'] = $processedResult['books_inserted'];
							$this->log->writeLog('/index', '', json_encode($data), '/log/log_');
							$this->view('uploadedData', $data);
						}
					} else {
						$data = "Nothing to insert";
						$this->log->writeLog('/index', '', json_encode($data), '/log/log_');
						$this->view('/error/error_page', $data);
					}
	        	} else {
	        		$data = 'Data Not Found';
	        		$this->log->writeLog('/index', '', json_encode($data), '/log/log_');
	        		$this->view('/error/error_page', $data);
	        	}
	        } else {
	        	$this->view('/error/error_page', $data);
	        }
    	} catch (Exception $e){
    		$data = $e->getMessage();
    		$this->log->writeLog('/index', '', json_encode($data), '/log/log_');
    		$this->view('/error/error_page', $data);
    	}
    }

    /*Parsing the XML data and sanitasing the data.*/
    function readContent ($content){
		try{
			if(gettype($content) != 'array' || count($content) == 0){
				throw new Exception("Invalid Input Provided");
			} 
			$parsedData = array();
			foreach ($content as $key => $value) {
				/*Getting XML data into normal xml object.*/
				$data = simplexml_load_string($value);
				if($data){
					$author = '';$name = '';
					if(isset($data->author)) $author = trim($data->author);
					if(isset($data->name)) $name = trim($data->name);
					$nameValidation = "/^[a-zA-Z0-9 ]*$/";
					if(preg_match($nameValidation, $author) && preg_match($nameValidation, $name)){
						$parsedData[$key]['author'] = $author;
						$parsedData[$key]['name'] = $name;
					}
				}
			}
			if(count($parsedData) > 0)
				return ['status' => 1, 'data' => $parsedData, 'message' => ''];
			else
				return ['status' => 0, 'message' => 'No Valid Data Available'];
		} catch (Exception $e){
			return ['status' => 0, 'message' => $e->getMessage()];
		}
	}
}
