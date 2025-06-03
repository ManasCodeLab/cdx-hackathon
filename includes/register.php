<?php
require_once __DIR__ . '/db_connect.php';

function validateRegistration($data) {
    $errors = [];
    
    // Validate name
    if (empty($data['name']) || strlen($data['name']) > 100) {
        $errors[] = "Name is required and must be less than 100 characters";
    }
    
    // Validate email
    if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    
    // Validate GitHub username
    if (empty($data['github_username']) || strlen($data['github_username']) > 50) {
        $errors[] = "GitHub username is required and must be less than 50 characters";
    }
    
    // Validate team data if team registration
    if ($data['registration_type'] === 'team') {
        if (empty($data['team_name'])) {
            $errors[] = "Team name is required for team registration";
        }
        if (empty($data['team_members'])) {
            $errors[] = "Team members are required for team registration";
        }
    }
    
    return $errors;
}

function processRegistration($data) {
    global $pdo;
    
    try {
        $pdo->beginTransaction();
        
        // Handle team registration if applicable
        $team_id = null;
        if ($data['registration_type'] === 'team') {
            $stmt = $pdo->prepare("INSERT INTO teams (team_name, team_members) VALUES (?, ?)");
            $stmt->execute([$data['team_name'], $data['team_members']]);
            $team_id = $pdo->lastInsertId();
        }
        
        // Calculate final amount
        $original_fee = REGISTRATION_FEE;
        $discount_amount = 0;
        $final_amount = $original_fee;
        
        if (!empty($data['coupon'])) {
            $stmt = $pdo->prepare("SELECT * FROM coupons WHERE code = ? AND status = 'active' AND valid_until > NOW()");
            $stmt->execute([$data['coupon']]);
            $coupon = $stmt->fetch();
            
            if ($coupon) {
                $discount_amount = ($original_fee * $coupon['discount_percentage']) / 100;
                $final_amount = $original_fee - $discount_amount;
                
                // Update coupon usage
                $stmt = $pdo->prepare("UPDATE coupons SET current_uses = current_uses + 1 WHERE id = ?");
                $stmt->execute([$coupon['id']]);
            }
        }
        
        // Insert registration
        $stmt = $pdo->prepare("
            INSERT INTO registrations (
                name, email, github_username, registration_type, team_id,
                registration_fee, coupon_applied, discount_amount, final_amount
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $data['name'],
            $data['email'],
            $data['github_username'],
            $data['registration_type'],
            $team_id,
            $original_fee,
            $data['coupon'] ?? null,
            $discount_amount,
            $final_amount
        ]);
        
        $pdo->commit();
        return ['success' => true, 'registration_id' => $pdo->lastInsertId()];
        
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Registration failed: " . $e->getMessage());
        return ['success' => false, 'error' => 'Registration failed. Please try again.'];
    }
}

// Handle AJAX registration request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $data = [
        'name' => $_POST['name'] ?? '',
        'email' => $_POST['email'] ?? '',
        'github_username' => $_POST['github_username'] ?? '',
        'registration_type' => $_POST['registration_type'] ?? 'solo',
        'team_name' => $_POST['team_name'] ?? '',
        'team_members' => $_POST['team_members'] ?? '',
        'coupon' => $_POST['coupon'] ?? ''
    ];
    
    $errors = validateRegistration($data);
    
    if (empty($errors)) {
        $result = processRegistration($data);
        echo json_encode($result);
    } else {
        echo json_encode(['success' => false, 'errors' => $errors]);
    }
    exit;
}
?> 