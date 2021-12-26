<?php
    class Database extends Controller{
        private $dbHost = DB_HOST;
        private $dbUser = DB_USER;
        private $dbPass = DB_PASS;
        private $dbName = DB_NAME;

        private $statement;
        private $dbHandler;
        private $error;

        public function __construct() {
            $this->log = new Logger();
            $conn = 'pgsql:host=' . $this->dbHost . ';dbname=' . $this->dbName;
            $options = array(
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            );
            try {
                $this->dbHandler = new PDO($conn, $this->dbUser, $this->dbPass, $options);
            } catch (PDOException $e) {
                $this->error = $e->getMessage();
                $data = $this->error;
                $this->view('/error/error_page', $data);
            }
        }

        /*Allows us to write queries*/
        public function query($sql) {
            $this->statement = $this->dbHandler->prepare($sql);
        }

        /*Starting transaction*/
        public function beginTransaction(){
            return $this->dbHandler->beginTransaction();
        }

        /*Starting transaction*/
        public function commitTransaction(){
            return $this->dbHandler->commit();
        }

        /*Starting transaction*/
        public function rollBackTransaction(){
            return $this->dbHandler->rollBack();
        }

        /*Bind values*/
        public function bind($parameter, $value, $type = null) {
            switch (is_null($type)) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                case is_array($value):
                    $type = null;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
            $this->statement->bindValue($parameter, $value, $type);
        }

        /*Execute the prepared statement*/
        public function execute($data = null) {
            $this->log->writeDBQueryLog(json_encode($this->statement), '/log/queryLogger/log_');
            return $this->statement->execute($data);
        }

        /*Return an array*/
        public function resultSet() {
            $this->execute();
            return $this->statement->fetchAll(PDO::FETCH_OBJ);
        }

        /*Return an associative array or result*/
        public function resultSetAssoc() {
            $this->execute();
            return $this->statement->fetchAll(PDO::FETCH_ASSOC);
        }

        /*Return a specific row as an object*/
        public function single() {
            $this->execute();
            return $this->statement->fetch(PDO::FETCH_OBJ);
        }

        /*Get's the row count*/
        public function rowCount() {
            return $this->statement->rowCount();
        }
    }
