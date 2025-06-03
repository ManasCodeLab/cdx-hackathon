<?php
// Prevent any output before headers
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

require_once 'config.php';
require_once 'db_connect.php';

// Set JSON header
header('Content-Type: application/json');

// Handle CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
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
    // Validate required fields
    if (empty($_POST['email']) || empty($_POST['registration_id'])) {
        throw new Exception('Email and registration ID are required');
    }
    
    // Check if registration exists
    $stmt = $pdo->prepare("SELECT id FROM registrations WHERE email = ? AND id = ? AND status = 'pending'");
    $stmt->execute([$_POST['email'], $_POST['registration_id']]);
    if (!$stmt->fetch()) {
        throw new Exception('Invalid registration');
    }
    
    // Validate file upload
    if (!isset($_FILES['screenshot']) || $_FILES['screenshot']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('No screenshot uploaded or upload error occurred');
    }
    
    $file = $_FILES['screenshot'];
    
    // Validate file type
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
    if (!in_array($file['type'], $allowed_types)) {
        throw new Exception('Only JPG and PNG files are allowed');
    }
    
    // Validate file size (max 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        throw new Exception('File size should not exceed 5MB');
    }
    
    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('payment_') . '_' . time() . '.' . $extension;
    $upload_path = __DIR__ . '/payment_screenshots/' . $filename;
    
    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
        throw new Exception('Failed to save screenshot');
    }
    
    // Update registration with screenshot path
    $stmt = $pdo->prepare("
        UPDATE registrations 
        SET payment_screenshot = ?, 
            status = 'payment_uploaded',
            updated_at = NOW()
        WHERE id = ? AND email = ?
    ");
    
    $stmt->execute([$filename, $_POST['registration_id'], $_POST['email']]);
    
    // Log successful upload
    error_log("Payment screenshot uploaded for registration ID: {$_POST['registration_id']}, Email: {$_POST['email']}");
    
    // Send success response
    $response = [
        'success' => true,
        'message' => 'Payment screenshot uploaded successfully. We will verify your payment shortly.'
    ];
    
    echo json_encode($response);
    exit;
    
} catch (Exception $e) {
    error_log('Screenshot Upload Error: ' . $e->getMessage());
    
    $response = [
        'success' => false,
        'error' => $e->getMessage()
    ];
    
    http_response_code(400);
    echo json_encode($response);
    exit;
} 