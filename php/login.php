<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../database/db.php';
require_once __DIR__ . '/../database/redis.php';

// Get POST data
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

// Validation
if (empty($email) || empty($password)) {
    echo json_encode([
        'success' => false,
        'message' => 'All fields are required'
    ]);
    exit;
}

// Database connection
$db = new Database();
$conn = $db->getConnection();

// Check user credentials using prepared statement
$stmt = $conn->prepare("SELECT id, username, email, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid email or password'
    ]);
    $stmt->close();
    $db->close();
    exit;
}

$user = $result->fetch_assoc();
$stmt->close();
$db->close();

// Verify password
if (!password_verify($password, $user['password'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid email or password'
    ]);
    exit;
}

// Create session token
$sessionToken = bin2hex(random_bytes(32));

// Store session in Redis
$redis = new RedisConnection();
$sessionData = json_encode([
    'userId' => $user['id'],
    'username' => $user['username'],
    'email' => $user['email'],
    'loginTime' => time()
]);

$redis->set('session:' . $sessionToken, $sessionData, SESSION_LIFETIME);

echo json_encode([
    'success' => true,
    'message' => 'Login successful',
    'sessionToken' => $sessionToken,
    'userId' => $user['id']
]);
?>
