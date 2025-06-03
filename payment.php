<?php
// Prevent any output before headers
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

require_once 'config.php';
require_once 'db_connect.php';

// Set JSON header for error responses
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
    // Get POST data (support both JSON and form-data)
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (stripos($contentType, 'application/json') !== false) {
        $raw = file_get_contents("php://input");
        $data = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON data received');
        }
    } else {
        $data = $_POST;
    }
    
    // Validate required fields
    if (empty($data['email']) || empty($data['amount'])) {
        throw new Exception('Email and amount are required');
    }
    
    // Validate amount
    $amount = floatval($data['amount']);
    if ($amount <= 0 || $amount > REGISTRATION_FEE) {
        throw new Exception('Invalid amount');
    }
    
    // Validate email format
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }
    
    // Check if registration exists
    $stmt = $pdo->prepare("SELECT id, name FROM registrations WHERE email = ? AND status = 'pending'");
    $stmt->execute([$data['email']]);
    $registration = $stmt->fetch();
    
    if (!$registration) {
        throw new Exception('No pending registration found for this email');
    }
    
    // Generate UPI link
    $upi_id = "arora.11@superyes";
    $name = "Manas Arora";
    $transaction_note = "CodeGenX Hackathon 2025";
    $currency = "INR";
    
    // Encode parameters for UPI URL
    $upi_params = [
        'pa' => $upi_id,
        'pn' => urlencode($name),
        'am' => number_format($amount, 2, '.', ''),
        'tn' => urlencode($transaction_note),
        'cu' => $currency
    ];
    
    // Build UPI URL
    $upi_link = "upi://pay?" . http_build_query($upi_params);
    
    // Log payment attempt
    error_log("Payment initiated for email: {$data['email']}, Amount: {$amount}");
    
    // Return success response with UPI link
    $response = [
        'success' => true,
        'upi_link' => $upi_link,
        'amount' => $amount,
        'registration_id' => $registration['id']
    ];
    
    echo json_encode($response);
    exit;
    
} catch (Exception $e) {
    error_log('Payment Error: ' . $e->getMessage());
    
    $response = [
        'success' => false,
        'error' => $e->getMessage()
    ];
    
    http_response_code(400);
    echo json_encode($response);
    exit;
} 