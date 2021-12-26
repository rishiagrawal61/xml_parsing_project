<?php
class DataModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /*Verifying and inserting parsed data from xml file to database.*/
    function insertIntoDB($insertData){
        try{
            $author = array();
            $authorNew = array();
            foreach ($insertData as $key => $value) {
                $author[$key] = "'".(string)$value['author']."'";
                $authorNew[$key] = (string)$value['author'];
                $name[$key] = (string)$value['name'];
            }
            /*Transaction Start*/
            if(!$this->db->beginTransaction())
                throw new Exception("Could not start transaction");
            /*Checking if author already exist or not*/
            $this->db->query('SELECT * FROM author WHERE author_name IN ('.implode(',',array_values($author)).')');
            $existingRows = $this->db->resultSet();
            if($existingRows){
                $data = $this->updateInsertExistingEntry($existingRows, $authorNew, $name);
                if(isset($data[0]))
                    $authorNew = $data[0];
                if(isset($data[1]))
                    $name = $data[1];
            }
            $authorNew = array_values($authorNew);
            $name = array_values($name);
            $authorArray = array();$newAuthorArray = array();
            $insertAuthorString = '';
            if(count($authorNew) > 0){
            	/*Taking only unique authors to be inserted into database.*/
            	$data = $this->authorQueryFormation($authorNew, $authorArray, $insertAuthorString);
                if(isset($data[0]))
                    $authorArray = $data[0];
                if(isset($data[1]))
                    $insertAuthorString = $data[1];
            }
            if($insertAuthorString <> ''){
            	/*Forming an array for binding values for authors.*/
            	foreach ($authorArray as $key => $value) {
                	$newAuthorArray = array_merge($newAuthorArray, array($key, $value));
                }
                $this->db->query($insertAuthorString);
                $result = $this->db->execute($newAuthorArray);
                if (!$result) {
                    throw new Exception("An error occurred.");
                }
                $insertBookString = '';
                $author = array();
                $newAuthEntryAssoc = array();$insertBookString = '';
                $data = $this->booksQueryFormation($authorNew, $author, $newAuthEntryAssoc, $insertBookString, $name);
                if(isset($data[0]))
                    $insertBookString = $data[0];
                if(isset($data[1]))
                    $newAuthEntryAssoc = $data[1];
                if($insertBookString <> ''){
                    $this->db->query($insertBookString);
                    $values = array();
                    $counter = 0;
                    foreach ($name as $value) {
                        $id = '';
                        if(isset($newAuthEntryAssoc[$authorNew[$counter]]))
                            $id = $newAuthEntryAssoc[$authorNew[$counter]];
                        /*binding the values for the query written for inseting into books table.*/
                        $values = array_merge($values,array($value, intval($id)));
                        $counter++;
                    }
                    $result = $this->db->execute($values);
                    if (!$result) {
                        throw new Exception("An error occurred.");
                    }
                }
            }
            /*Transaction Commit*/
            if(!$this->db->commitTransaction())
                throw new Exception("Transaction commit failed\n");
            return ['status' => 1, 'author_inserted' => $authorNew, 'books_inserted' => $name, 'message' => 'Unique Inserted Successfully'];
        } catch (Exception $e){
            /*Transaction Rollback*/
            $this->db->rollBackTransaction() or die("Transaction rollback failed\n");
            return ['status' => 0, 'message' => $e->getMessage()];
        }
    }

    /*Unpdating author book count and inserting books if author already exists.*/
    public function updateInsertExistingEntry($existingRows, $authorNew, $name){
        foreach ($existingRows as $value) {
            $key = array();
            $key = array_keys($authorNew, $value->author_name);
            if(count($key) > 0){
                for ($i = 0;$i < count($key); $i++){
                    if($authorNew[$key[$i]] == $value->author_name){
                        /*Checking if for that author already book record inserted or not*/
                        $this->db->query('SELECT * FROM books WHERE author_id = :id AND book_name = :name');
                        $this->db->bind(':id', $value->id);
                        $this->db->bind(':name', $name[$key[$i]]);
                        $bookData = $this->db->resultSet();
                        if($this->db->rowCount() == 0){
                            /*Updating the count of each author, telling number of books published*/
                            $this->db->query("UPDATE author SET count = count+1 WHERE id = :id");
                            $this->db->bind(':id', $value->id);
                            $this->db->execute();
                            /*Inserting book record into table*/
                            $this->db->query("INSERT INTO books (author_id, book_name) VALUES (:id, :name)");
                            $this->db->bind(':id', $value->id);
                            $this->db->bind(':name', $name[$key[$i]]);
                            $this->db->execute();
                            }
                        unset($authorNew[$key[$i]]);
                        unset($name[$key[$i]]);
                    }
                }
            }
        }
        return array($authorNew, $name);
    }

    /*Used for formation of query for bulk author entry*/
    public function authorQueryFormation($authorNew, $authorArray, $insertAuthorString){
        foreach ($authorNew as $key) {
            if(isset($authorArray[$key]))
                $authorArray[$key]+=1;
            else
                $authorArray[$key]=1;
        }
        /*query formation for inerting new author to table*/
        $insertAuthorString = 'INSERT INTO author (author_name, count) VALUES ';
        $counter = 0;
        foreach ($authorArray as $value) {
            if($counter == 0)
                $insertAuthorString.="(?, ?)";
            else
                $insertAuthorString.=",(?, ?)";
            $counter++;
        }
        return array($authorArray, $insertAuthorString);
    }

    /*Used for formation of query for bulk books entry*/
    public function booksQueryFormation($authorNew, $author, $newAuthEntryAssoc, $insertBookString, $name){
        foreach ($authorNew as $key => $value) {
            $author[$key] = "'".$value."'";
        }
        /*Fetching the ID of new authors added*/
        $this->db->query('SELECT * FROM author WHERE author_name IN ('.implode(',', array_unique(array_values($author))).')');
        $dataReturned = $this->db->resultSetAssoc();
        if(count($dataReturned) <> 0){
            foreach ($dataReturned as $value) {
                $newAuthEntryAssoc[$value['author_name']] = $value['id'];
            }
        }
        $counter = 0;
        /*Query formation for inserting books to table*/
        $insertBookString = 'INSERT INTO books (book_name, author_id) VALUES ';
        foreach ($name as $value) {
            if($counter == 0)
                $insertBookString.="(?, ?)";
            else
                $insertBookString.=",(?, ?)";
            $counter++;
        }
        return array($insertBookString, $newAuthEntryAssoc);
    }
}
?>
