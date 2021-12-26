<?php
class DisplayModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }
    /*Returning List of all the existing authors*/
    public function getExistingAuthors(){
        $this->db->query('SELECT * FROM author');
        return  $this->db->resultSet();
    }

    /*Returning List of All books published by particular Author.*/
    public function getBookListingForAuthor($author_id = 0){
        try{
            $this->db->query('SELECT id, book_name FROM books WHERE author_id = :author');
            $this->db->bind(':author', $author_id);
            return ['status' => 1, 'data' => $this->db->resultSet(), 'message' => ''];
        } catch (Exception $e){
            return ['status' => 0, 'message' => $e->getMessage()];
        }
    }
}
?>