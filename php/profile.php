<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../database/db.php';
require_once __DIR__ . '/../database/mongodb.php';
require_once __DIR__ . '/../database/redis.php';

// Get request data
$sessionToken = isset($_GET['sessionToken']) ? trim($_GET['sessionToken']) : '';
$userId = isset($_GET['userId']) ? trim($_GET['userId']) : '';

// Validation
if (empty($sessionToken) || empty($userId)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request'
    ]);
    exit;
}

// Verify session from Redis
$redis = new RedisConnection();
$sessionData = $redis->get('session:' . $sessionToken);

if (!$sessionData) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid session'
    ]);
    exit;
}

$session = json_decode($sessionData, true);

if ($session['userId'] != $userId) {
    echo json_encode([
        'success' => false,
        'message' => 'Unauthorized access'
    ]);
    exit;
}

// Get user data from MySQL
$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->prepare("SELECT id, username, email, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'User not found'
    ]);
    $stmt->close();
    $db->close();
    exit;
}

$user = $result->fetch_assoc();
$stmt->close();
$db->close();

// Get additional profile data from MongoDB
$mongo = new MongoDB_Connection();
$profileData = $mongo->findDocument('profiles', ['userId' => (int)$userId]);

// Merge data
$userData = [
    'id' => $user['id'],
    'username' => $user['username'],
    'email' => $user['email'],
    'age' => isset($profileData['age']) ? $profileData['age'] : null,
    'dob' => isset($profileData['dob']) ? $profileData['dob'] : null,
    'contact' => isset($profileData['contact']) ? $profileData['contact'] : null
];

echo json_encode([
    'success' => true,
    'user' => $userData
]);
?>
