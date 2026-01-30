<?php
require_once __DIR__ . '/../config/config.php';

class RedisConnection {
    private $redis;
    
    public function __construct() {
        try {
            $this->redis = new Redis();
            $this->redis->connect(REDIS_HOST, REDIS_PORT);
        } catch (Exception $e) {
            die("Redis connection error: " . $e->getMessage());
        }
    }
    
    public function set($key, $value, $expiry = null) {
        try {
            if ($expiry) {
                return $this->redis->setex($key, $expiry, $value);
            } else {
                return $this->redis->set($key, $value);
            }
        } catch (Exception $e) {
            error_log("Redis set error: " . $e->getMessage());
            return false;
        }
    }
    
    public function get($key) {
        try {
            return $this->redis->get($key);
        } catch (Exception $e) {
            error_log("Redis get error: " . $e->getMessage());
            return false;
        }
    }
    
    public function delete($key) {
        try {
            return $this->redis->del($key);
        } catch (Exception $e) {
            error_log("Redis delete error: " . $e->getMessage());
            return false;
        }
    }
    
    public function exists($key) {
        try {
            return $this->redis->exists($key);
        } catch (Exception $e) {
            error_log("Redis exists error: " . $e->getMessage());
            return false;
        }
    }
}
?>
