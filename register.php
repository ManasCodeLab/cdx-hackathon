<?php
// Prevent any output before headers
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

require_once 'config.php';
require_once 'db_connect.php';
require_once 'mailer.php';

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
    
    // Log received data for debugging
    error_log('Received data: ' . print_r($data, true));
    
    // Basic validation
    if (empty($data['name']) || empty($data['email']) || empty($data['github_username'])) {
        throw new Exception('Name, email, and GitHub username are required');
    }
    
    // Validate email format
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }
    
    // Start transaction
    $pdo->beginTransaction();
    
    // Check for existing email
    $stmt = $pdo->prepare("SELECT id FROM registrations WHERE email = ?");
    $stmt->execute([$data['email']]);
    if ($stmt->fetch()) {
        throw new Exception('This email is already registered. Please use a different email address.');
    }
    
    // Handle coupon if provided
    $discount_amount = 0;
    if (!empty($data['coupon'])) {
        $stmt = $pdo->prepare("
            SELECT discount_percentage 
            FROM coupons 
            WHERE code = ? 
            AND status = 'active' 
            AND is_active = 1 
            AND valid_until > NOW()
        ");
        $stmt->execute([strtoupper($data['coupon'])]);
        $coupon = $stmt->fetch();
        
        if ($coupon) {
            $discount_amount = (REGISTRATION_FEE * $coupon['discount_percentage']) / 100;
        }
    }
    
    // Calculate final amount
    $final_amount = REGISTRATION_FEE - $discount_amount;
    
    // Insert registration
    $stmt = $pdo->prepare("
        INSERT INTO registrations (
            name, email, github_username, registration_type,
            team_name, team_members, amount_paid, coupon_code,
            status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')
    ");
    
    $stmt->execute([
        $data['name'],
        $data['email'],
        $data['github_username'],
        $data['registration_type'] ?? 'solo',
        $data['team_name'] ?? null,
        $data['team_members'] ?? null,
        $final_amount,
        !empty($data['coupon']) ? strtoupper($data['coupon']) : null
    ]);
    
    // Check for SQL errors
    if ($stmt->errorCode() !== '00000') {
        throw new Exception('Insert failed: ' . implode(', ', $stmt->errorInfo()));
    }
    
    // Commit transaction
    $pdo->commit();
    
    // Send confirmation email
    $emailBody = "
        <h2>Welcome to CDX Hackathon!</h2>
        <p>Dear {$data['name']},</p>
        <p>Thank you for registering for the CDX Hackathon. Your registration has been received successfully.</p>
        <p><strong>Registration Details:</strong></p>
        <ul>
            <li>Name: {$data['name']}</li>
            <li>Email: {$data['email']}</li>
            <li>GitHub Username: {$data['github_username']}</li>
            <li>Registration Type: " . ($data['registration_type'] ?? 'solo') . "</li>
            " . (!empty($data['team_name']) ? "<li>Team Name: {$data['team_name']}</li>" : "") . "
            <li>Amount Paid: ₹{$final_amount}</li>
            " . (!empty($data['coupon']) ? "<li>Coupon Applied: {$data['coupon']}</li>" : "") . "
        </ul>
        <p>We look forward to seeing you at the hackathon!</p>
        <p>Best regards,<br>CDX Hackathon Team</p>
    ";
    
    $emailSent = sendConfirmationMail(
        $data['email'],
        $data['name'],
        'CDX Hackathon Registration Confirmation',
        $emailBody
    );
    
    if (!$emailSent) {
        error_log('Failed to send confirmation email to: ' . $data['email']);
    }
    
    // Send success response
    $response = [
        'success' => true,
        'message' => 'Registration successful! A confirmation email has been sent to your email address.'
    ];
    
    echo json_encode($response);
    exit;
    
} catch (PDOException $e) {
    // Rollback transaction on error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    error_log('Database Error: ' . $e->getMessage());
    
    // Check for duplicate email error
    if ($e->getCode() == 23000 && strpos($e->getMessage(), 'email') !== false) {
        $response = [
            'success' => false,
            'error' => 'This email is already registered. Please use a different email address.'
        ];
    } else {
        $response = [
            'success' => false,
            'error' => 'Database error occurred. Please try again.'
        ];
    }
    
    http_response_code(500);
    echo json_encode($response);
    exit;
    
} catch (Exception $e) {
    // Rollback transaction on error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    error_log('Registration Error: ' . $e->getMessage());
    
    // Send error response
    $response = [
        'success' => false,
        'error' => $e->getMessage()
    ];
    
    http_response_code(400);
    echo json_encode($response);
    exit;
} 