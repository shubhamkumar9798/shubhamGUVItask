<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../database/db.php';
require_once __DIR__ . '/../database/mongodb.php';
require_once __DIR__ . '/../database/redis.php';

// Get POST data
$sessionToken = isset($_POST['sessionToken']) ? trim($_POST['sessionToken']) : '';
$userId = isset($_POST['userId']) ? trim($_POST['userId']) : '';
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$age = isset($_POST['age']) ? trim($_POST['age']) : null;
$dob = isset($_POST['dob']) ? trim($_POST['dob']) : null;
$contact = isset($_POST['contact']) ? trim($_POST['contact']) : null;

// Validation
if (empty($sessionToken) || empty($userId) || empty($username)) {
    echo json_encode([
        'success' => false,
        'message' => 'Required fields are missing'
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

// Update username in MySQL
$db = new Database();
$conn = $db->getConnection();

$stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
$stmt->bind_param("si", $username, $userId);
$stmt->execute();
$stmt->close();
$db->close();

// Update additional details in MongoDB
$mongo = new MongoDB_Connection();

$profileData = [
    'userId' => (int)$userId,
    'age' => $age,
    'dob' => $dob,
    'contact' => $contact,
    'updated_at' => date('Y-m-d H:i:s')
];

// Check if profile exists
$existingProfile = $mongo->findDocument('profiles', ['userId' => (int)$userId]);

if ($existingProfile) {
    // Update existing profile
    $mongo->updateDocument('profiles', ['userId' => (int)$userId], $profileData);
} else {
    // Insert new profile
    $mongo->insertDocument('profiles', $profileData);
}

echo json_encode([
    'success' => true,
    'message' => 'Profile updated successfully'
]);
?>
