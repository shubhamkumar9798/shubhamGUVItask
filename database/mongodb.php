<?php
require_once __DIR__ . '/../config/config.php';

class MongoDB_Connection {
    private $client;
    private $database;
    
    public function __construct() {
        try {
            $connectionString = "mongodb://" . MONGO_HOST . ":" . MONGO_PORT;
            $this->client = new MongoDB\Driver\Manager($connectionString);
            $this->database = MONGO_DB;
        } catch (Exception $e) {
            die("MongoDB connection error: " . $e->getMessage());
        }
    }
    
    public function insertDocument($collection, $document) {
        try {
            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->insert($document);
            
            $result = $this->client->executeBulkWrite($this->database . '.' . $collection, $bulk);
            return $result->getInsertedCount() > 0;
        } catch (Exception $e) {
            error_log("MongoDB insert error: " . $e->getMessage());
            return false;
        }
    }
    
    public function findDocument($collection, $filter) {
        try {
            $query = new MongoDB\Driver\Query($filter);
            $cursor = $this->client->executeQuery($this->database . '.' . $collection, $query);
            
            foreach ($cursor as $document) {
                return (array) $document;
            }
            return null;
        } catch (Exception $e) {
            error_log("MongoDB find error: " . $e->getMessage());
            return null;
        }
    }
    
    public function updateDocument($collection, $filter, $update) {
        try {
            $bulk = new MongoDB\Driver\BulkWrite;
            $bulk->update($filter, ['$set' => $update], ['multi' => false, 'upsert' => false]);
            
            $result = $this->client->executeBulkWrite($this->database . '.' . $collection, $bulk);
            return $result->getModifiedCount() > 0;
        } catch (Exception $e) {
            error_log("MongoDB update error: " . $e->getMessage());
            return false;
        }
    }
}
?>
