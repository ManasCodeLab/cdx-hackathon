<?php
require_once '../config.php';
require_once '../db_connect.php';

// Basic admin authentication (you should implement proper authentication)
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Handle confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    try {
        if ($_POST['action'] === 'confirm') {
            $stmt = $pdo->prepare("
                UPDATE registrations 
                SET status = 'confirmed',
                    updated_at = NOW()
                WHERE id = ? AND status = 'payment_uploaded'
            ");
            $stmt->execute([$_POST['registration_id']]);
            
            // Get registration details for email
            $stmt = $pdo->prepare("SELECT * FROM registrations WHERE id = ?");
            $stmt->execute([$_POST['registration_id']]);
            $registration = $stmt->fetch();
            
            if ($registration) {
                // Send confirmation email
                $emailBody = "
                    <h2>Registration Confirmed!</h2>
                    <p>Dear {$registration['name']},</p>
                    <p>Your registration for CodeGenX Hackathon 2025 has been confirmed.</p>
                    <p><strong>Registration Details:</strong></p>
                    <ul>
                        <li>Name: {$registration['name']}</li>
                        <li>Email: {$registration['email']}</li>
                        <li>GitHub Username: {$registration['github_username']}</li>
                        <li>Registration Type: {$registration['registration_type']}</li>
                        " . (!empty($registration['team_name']) ? "<li>Team Name: {$registration['team_name']}</li>" : "") . "
                        <li>Amount Paid: ₹{$registration['amount_paid']}</li>
                    </ul>
                    <p>We look forward to seeing you at the hackathon!</p>
                    <p>Best regards,<br>CodeGenX Hackathon Team</p>
                ";
                
                require_once '../mailer.php';
                sendConfirmationMail(
                    $registration['email'],
                    $registration['name'],
                    'CodeGenX Hackathon 2025 - Registration Confirmed',
                    $emailBody
                );
            }
            
            $_SESSION['success'] = 'Registration confirmed successfully';
        } elseif ($_POST['action'] === 'cancel') {
            $stmt = $pdo->prepare("
                UPDATE registrations 
                SET status = 'cancelled',
                    updated_at = NOW()
                WHERE id = ? AND status = 'payment_uploaded'
            ");
            $stmt->execute([$_POST['registration_id']]);
            $_SESSION['success'] = 'Registration cancelled';
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'Error: ' . $e->getMessage();
    }
    
    header('Location: confirm_registrations.php');
    exit;
}

// Get pending registrations
$stmt = $pdo->query("
    SELECT * FROM registrations 
    WHERE status = 'payment_uploaded' 
    ORDER BY registration_date DESC
");
$registrations = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirm Registrations - CodeGenX Hackathon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4f46e5',
                        accent: '#0ea5e9',
                        darkBg: '#18181b',
                        card: '#232334',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <span class="text-accent text-2xl font-mono">{"<"}</span>
                    <span class="text-xl font-bold ml-2">Confirm Registrations</span>
                    <span class="text-accent text-2xl font-mono ml-2">{"/>"}</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="index.php" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                    <a href="logout.php" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php 
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>
        
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Pending Registrations</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Screenshot</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($registrations as $reg): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($reg['name']); ?></div>
                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars($reg['github_username']); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?php echo htmlspecialchars($reg['email']); ?></div>
                                <div class="text-sm text-gray-500"><?php echo $reg['registration_type']; ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">₹<?php echo number_format($reg['amount_paid'], 2); ?></div>
                                <?php if ($reg['coupon_code']): ?>
                                <div class="text-sm text-gray-500">Coupon: <?php echo htmlspecialchars($reg['coupon_code']); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($reg['payment_screenshot']): ?>
                                <a href="../payment_screenshots/<?php echo htmlspecialchars($reg['payment_screenshot']); ?>" 
                                   target="_blank" 
                                   class="text-accent hover:text-accent/80">
                                    View Screenshot
                                </a>
                                <?php else: ?>
                                <span class="text-gray-500">No screenshot</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php echo date('M d, Y H:i', strtotime($reg['registration_date'])); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <form method="POST" class="inline-block">
                                    <input type="hidden" name="registration_id" value="<?php echo $reg['id']; ?>">
                                    <input type="hidden" name="action" value="confirm">
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 mr-2"
                                            onclick="return confirm('Are you sure you want to confirm this registration?')">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Confirm
                                    </button>
                                </form>
                                <form method="POST" class="inline-block">
                                    <input type="hidden" name="registration_id" value="<?php echo $reg['id']; ?>">
                                    <input type="hidden" name="action" value="cancel">
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                            onclick="return confirm('Are you sure you want to cancel this registration?')">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Cancel
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($registrations)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No pending registrations found
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>