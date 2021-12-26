<?php
interface DisplayInterface{
	/*Method to get all the authors available in the system.*/
	public function searchBookByAuthor();

	/*method to get the books published by particular publisher.*/
	public function getBookDetails();
}
?>