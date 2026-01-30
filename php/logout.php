<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../database/redis.php';

// Get POST data
$sessionToken = isset($_POST['sessionToken']) ? trim($_POST['sessionToken']) : '';

// Validation
if (empty($sessionToken)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request'
    ]);
    exit;
}

// Delete session from Redis
$redis = new RedisConnection();
$result = $redis->delete('session:' . $sessionToken);

echo json_encode([
    'success' => true,
    'message' => 'Logout successful'
]);
?>
