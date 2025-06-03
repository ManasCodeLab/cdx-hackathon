<?php
require_once __DIR__ . '/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $coupon_code = $_POST['coupon'] ?? '';
    
    if (empty($coupon_code)) {
        echo json_encode(['valid' => false, 'message' => 'Coupon code is required']);
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("
            SELECT * FROM coupons 
            WHERE code = ? 
            AND status = 'active' 
            AND valid_until > NOW()
            AND (max_uses IS NULL OR current_uses < max_uses)
        ");
        
        $stmt->execute([$coupon_code]);
        $coupon = $stmt->fetch();
        
        if ($coupon) {
            $discount_amount = (REGISTRATION_FEE * $coupon['discount_percentage']) / 100;
            $final_amount = REGISTRATION_FEE - $discount_amount;
            
            echo json_encode([
                'valid' => true,
                'discount_percentage' => $coupon['discount_percentage'],
                'discount_amount' => $discount_amount,
                'final_amount' => $final_amount
            ]);
        } else {
            echo json_encode(['valid' => false, 'message' => 'Invalid or expired coupon code']);
        }
    } catch (Exception $e) {
        error_log("Coupon validation failed: " . $e->getMessage());
        echo json_encode(['valid' => false, 'message' => 'Error validating coupon code']);
    }
    exit;
}
?> 