<?php
require_once('DisplayInterface.php');
class DisplayController extends Controller implements DisplayInterface{
	public function __construct() {
    	ini_set('display_errors', false);
        $this->displayModel = $this->model('DisplayModel');
        $this->log = new Logger();
    }


    public function searchBookByAuthor(){
		$authorListing = $this->displayModel->getExistingAuthors();
		$data['title'] = 'Search Published Books by Authors';
		$data['authorListing'] = $authorListing;
		$this->log->writeLog('/searchBookByAuthor', '', json_encode($data), '/log/log_');
		$this->view('searchBookByAuthor', $data);
	}

	/*Returning List of all the existing books for particular author*/
    public function getBookDetails(){
    	try{
    		if(!isset($_POST['author_id'])){
    			throw new Exception("Improper Input Supplied");
			}
	    	$pattern = "/^[0-9]+$/";
	    	if(!preg_match($pattern, $_POST['author_id'])){
	    		throw new Exception("Invalid Input Provided");
	    	}
	    	/*Fetching books listing*/
	    	$bookListing = $this->displayModel->getBookListingForAuthor($_POST['author_id']);
	    	if($bookListing['status'] == 0){
	    		throw new Exception($bookListing['message']);
	    	} else {
	    		$data ['booksListing'] = $bookListing['data'];
	    		$this->log->writeLog('/getBookDetails', '', json_encode(['status' => 1, 'data' => $data, 'message' => '']), '/log/log_');
	    		echo json_encode(['status' => 1, 'data' => $data, 'message' => '']);
	    	}
    	} catch (Exception $e){
    		$this->log->writeLog('/getBookDetails', '', json_encode(['status' => 0, 'message' =>$e->getMessage()]), '/log/log_');
    		echo json_encode(['status' => 0, 'message' =>$e->getMessage()]);
    	}
    }
}
?>