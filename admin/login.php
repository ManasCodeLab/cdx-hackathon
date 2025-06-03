<?php
// Include config first to set up session configuration
require_once '../config.php';

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Basic admin credentials (you should use a more secure method in production)
$admin_username = 'manas';
$admin_password = 'cdx2025'; // Change this to a secure password

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = 'Invalid request';
    } else {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // Add rate limiting
        $attempts = $_SESSION['login_attempts'] ?? 0;
        $last_attempt = $_SESSION['last_attempt'] ?? 0;
        
        // Reset attempts after 15 minutes
        if (time() - $last_attempt > 900) {
            $attempts = 0;
        }
        
        if ($attempts >= 5) {
            $error = 'Too many login attempts. Please try again later.';
        } else {
            if ($username === $admin_username && $password === $admin_password) {
                // Reset attempts on successful login
                unset($_SESSION['login_attempts']);
                unset($_SESSION['last_attempt']);
                
                // Regenerate session ID for security
                session_regenerate_id(true);
                
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['last_activity'] = time();
                
                header('Location: index.php');
                exit;
            } else {
                // Increment failed attempts
                $_SESSION['login_attempts'] = $attempts + 1;
                $_SESSION['last_attempt'] = time();
                $error = 'Invalid username or password';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - CodeGenX Hackathon</title>
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
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <span class="text-accent text-2xl font-mono">{"<"}</span>
                <span class="text-2xl font-bold ml-2">Admin Login</span>
                <span class="text-accent text-2xl font-mono ml-2">{"/>"}</span>
            </div>
            
            <?php if (isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>
            
            <form method="POST" class="space-y-6">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           required 
                           autocomplete="username"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required 
                           autocomplete="current-password"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-accent focus:ring-accent">
                </div>
                
                <button type="submit" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent hover:bg-accent/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent">
                    Login
                </button>
            </form>
        </div>
    </div>
</body>
</html> 