<?php
// Load environment variables from .env file if it exists
$dotenv = [];
if (file_exists('.env')) {
    $dotenv = parse_ini_file('.env');
}

// Database Configuration
define('DB_HOST', $dotenv['DB_HOST'] ?? 'db.be-mons1.bengt.wasmernet.com');
define('DB_NAME', $dotenv['DB_NAME'] ?? 'cdx_hack');
define('DB_USER', $dotenv['DB_USER'] ?? 'ec9913de7274800088a3ea66fe18');
define('DB_PASS', $dotenv['DB_PASS'] ?? '0683ec99-13de-73d4-8000-6fe44e84a868');

// SMTP Configuration
define('SMTP_HOST', $dotenv['SMTP_HOST'] ?? 'smtp.zoho.com');
define('SMTP_PORT', $dotenv['SMTP_PORT'] ?? 465);
define('SMTP_USER', $dotenv['SMTP_USER'] ?? 'admin@manas.eu.org');
define('SMTP_PASS', $dotenv['SMTP_PASS'] ?? 'hH8JBABqNzmq');
define('SMTP_FROM_EMAIL', $dotenv['SMTP_FROM_EMAIL'] ?? SMTP_USER);
define('SMTP_FROM_NAME', $dotenv['SMTP_FROM_NAME'] ?? 'CodeGenX');

// Application Settings
define('REGISTRATION_FEE', 100);
define('APP_URL', 'https://code.manas.eu.org/cdx-hackathon');
define('APP_NAME', $dotenv['APP_NAME'] ?? 'CodeGenX');
define('APP_ENV', $dotenv['APP_ENV'] ?? 'development');
define('APP_DEBUG', $dotenv['APP_DEBUG'] ?? 'true');

// Registration settings
define('DEFAULT_COUPON', $dotenv['DEFAULT_COUPON'] ?? 'WELCOME');
define('DEFAULT_DISCOUNT', $dotenv['DEFAULT_DISCOUNT'] ?? 0);

// Contact settings
define('CONTACT_EMAIL', $dotenv['CONTACT_EMAIL'] ?? SMTP_USER);
define('ADMIN_EMAIL', $dotenv['ADMIN_EMAIL'] ?? SMTP_USER);

// Security settings
define('SESSION_LIFETIME', $dotenv['SESSION_LIFETIME'] ?? 120);
define('CSRF_LIFETIME', $dotenv['CSRF_LIFETIME'] ?? 3600);

// Error reporting based on environment
if (APP_DEBUG === 'true') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/error.log');
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/error.log');
}

// Basic security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Credentials: true');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Session configuration
ini_set('session.gc_maxlifetime', SESSION_LIFETIME * 60);
ini_set('session.cookie_lifetime', SESSION_LIFETIME * 60);
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
ini_set('session.cookie_samesite', 'Lax');
session_start();

// Time zone
date_default_timezone_set($dotenv['TIMEZONE'] ?? 'Asia/Kolkata');
?> 