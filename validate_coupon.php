<?php
// Prevent any output before headers
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

require_once 'config.php';
require_once 'db_connect.php';

// Set JSON header
header('Content-Type: application/json');

// Handle CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

try {
    // Get coupon code from POST or JSON input
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (stripos($contentType, 'application/json') !== false) {
        $raw = file_get_contents("php://input");
        $data = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON data received');
        }
        $code = isset($data['coupon']) ? strtoupper(trim($data['coupon'])) : '';
    } else {
        $code = isset($_POST['coupon']) ? strtoupper(trim($_POST['coupon'])) : '';
    }
    
    if (empty($code)) {
        throw new Exception('Coupon code is required');
    }
    
    // Log coupon validation attempt
    error_log("Validating coupon: " . $code);
    
    // Validate coupon
    $stmt = $pdo->prepare("
        SELECT discount_percentage 
        FROM coupons 
        WHERE code = ? 
        AND status = 'active' 
        AND is_active = 1 
        AND valid_until > NOW()
    ");
    $stmt->execute([$code]);
    $coupon = $stmt->fetch();
    
    if (!$coupon) {
        throw new Exception('Invalid or expired coupon code');
    }
    
    // Calculate discount
    $discount_amount = (REGISTRATION_FEE * $coupon['discount_percentage']) / 100;
    $final_amount = REGISTRATION_FEE - $discount_amount;
    
    // Send success response
    $response = [
        'success' => true,
        'valid' => true,
        'discount_percent' => $coupon['discount_percentage'],
        'discount_amount' => $discount_amount,
        'final_amount' => $final_amount
    ];
    
    echo json_encode($response);
    exit;
    
} catch (Exception $e) {
    // Log error
    error_log("Coupon validation error: " . $e->getMessage());
    
    // Send error response
    $response = [
        'success' => false,
        'valid' => false,
        'message' => $e->getMessage()
    ];
    
    http_response_code(400);
    echo json_encode($response);
    exit;
}
?> 