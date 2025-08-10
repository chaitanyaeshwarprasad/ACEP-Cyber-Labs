<?php
/**
 * CCST Labs Configuration File
 * For educational use only – © Chaitanya Cyber Strix Technologies Pvt. Ltd.
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'ccst_labs');
define('DB_USER', 'ccst_user');
define('DB_PASS', 'ccst_password_2024');
define('DB_CHARSET', 'utf8mb4');

// Application Configuration
define('APP_NAME', 'CCST Labs');
define('APP_VERSION', '2.0');
define('APP_DESCRIPTION', 'Secure Code Review Lab');
define('APP_AUTHOR', 'Chaitanya Cyber Strix Technologies Pvt. Ltd.');

// Security Configuration
define('SESSION_TIMEOUT', 3600); // 1 hour
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_DURATION', 900); // 15 minutes
define('PASSWORD_MIN_LENGTH', 8);

// File Upload Configuration
define('UPLOAD_MAX_SIZE', 10485760); // 10MB
define('UPLOAD_ALLOWED_TYPES', [
    'image/jpeg',
    'image/png',
    'image/gif',
    'application/pdf',
    'text/plain'
]);
define('UPLOAD_DIR', 'uploads/');

// Error Reporting Configuration
define('DEBUG_MODE', true);
define('DISPLAY_ERRORS', true);
define('LOG_ERRORS', true);
define('ERROR_LOG_FILE', '/var/log/apache2/ccst-labs-error.log');

// Module Configuration
define('LABS_DIR', 'labs/');
define('TOTAL_MODULES', 15);
define('MODULE_COMPLETION_SCORE', 100);

// UI Configuration
define('THEME_DARK', true);
define('ANIMATIONS_ENABLED', true);
define('NOTIFICATIONS_ENABLED', true);

// Assessment Configuration
define('ASSESSMENT_ENABLED', true);
define('MIN_PASS_SCORE', 70);
define('MAX_ATTEMPTS_PER_MODULE', 3);

// Database connection function
function getDBConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        if (DEBUG_MODE) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        } else {
            throw new Exception("Database connection failed");
        }
    }
}

// Error handling function
function handleError($errno, $errstr, $errfile, $errline) {
    if (DEBUG_MODE) {
        $error_message = "Error [$errno]: $errstr in $errfile on line $errline";
    } else {
        $error_message = "An error occurred";
    }
    
    if (LOG_ERRORS && ERROR_LOG_FILE) {
        error_log(date('[Y-m-d H:i:s] ') . $error_message . PHP_EOL, 3, ERROR_LOG_FILE);
    }
    
    if (DISPLAY_ERRORS) {
        echo "<div style='color: red; padding: 10px; border: 1px solid red; margin: 10px;'>";
        echo htmlspecialchars($error_message);
        echo "</div>";
    }
    
    return true;
}

// Set error handler
set_error_handler('handleError');

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0); // Set to 1 for HTTPS
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Strict');

// Security headers
function setSecurityHeaders() {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Content-Security-Policy: default-src \'self\'; script-src \'self\' \'unsafe-inline\' cdnjs.cloudflare.com; style-src \'self\' \'unsafe-inline\' cdnjs.cloudflare.com; font-src cdnjs.cloudflare.com;');
}

// CSRF protection
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Input sanitization
function sanitizeInput($input) {
    if (is_array($input)) {
        return array_map('sanitizeInput', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// File upload validation
function validateFileUpload($file) {
    $errors = [];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'File upload failed';
        return $errors;
    }
    
    if ($file['size'] > UPLOAD_MAX_SIZE) {
        $errors[] = 'File size exceeds limit';
    }
    
    if (!in_array($file['type'], UPLOAD_ALLOWED_TYPES)) {
        $errors[] = 'File type not allowed';
    }
    
    return $errors;
}

// Module completion tracking
function markModuleCompleted($moduleName, $score = MODULE_COMPLETION_SCORE) {
    try {
        $pdo = getDBConnection();
        
        $stmt = $pdo->prepare("
            INSERT INTO module_completions (module_name, user_id, score, completed_at) 
            VALUES (?, ?, ?, NOW())
            ON DUPLICATE KEY UPDATE 
            score = GREATEST(score, VALUES(score)),
            completed_at = NOW()
        ");
        
        $userId = $_SESSION['user_id'] ?? 1; // Default user ID
        $stmt->execute([$moduleName, $userId, $score]);
        
        return true;
    } catch (Exception $e) {
        if (DEBUG_MODE) {
            error_log("Module completion tracking failed: " . $e->getMessage());
        }
        return false;
    }
}

// Get module progress
function getModuleProgress($userId = null) {
    try {
        $pdo = getDBConnection();
        
        if ($userId === null) {
            $userId = $_SESSION['user_id'] ?? 1;
        }
        
        $stmt = $pdo->prepare("
            SELECT module_name, score, completed_at 
            FROM module_completions 
            WHERE user_id = ?
        ");
        
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        if (DEBUG_MODE) {
            error_log("Module progress retrieval failed: " . $e->getMessage());
        }
        return [];
    }
}

// Logging function
function logActivity($action, $details = '', $userId = null) {
    try {
        $pdo = getDBConnection();
        
        if ($userId === null) {
            $userId = $_SESSION['user_id'] ?? 1;
        }
        
        $stmt = $pdo->prepare("
            INSERT INTO logs (user_id, action, details, ip_address, created_at) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $stmt->execute([$userId, $action, $details, $ipAddress]);
        
        return true;
    } catch (Exception $e) {
        if (DEBUG_MODE) {
            error_log("Activity logging failed: " . $e->getMessage());
        }
        return false;
    }
}

// Configuration validation
function validateConfiguration() {
    $errors = [];
    
    // Check database connection
    try {
        getDBConnection();
    } catch (Exception $e) {
        $errors[] = 'Database connection failed: ' . $e->getMessage();
    }
    
    // Check required directories
    $requiredDirs = [MODULES_DIR, UPLOAD_DIR];
    foreach ($requiredDirs as $dir) {
        if (!is_dir($dir)) {
            $errors[] = "Required directory missing: $dir";
        }
    }
    
    // Check file permissions
    if (!is_readable('index.php')) {
        $errors[] = 'index.php is not readable';
    }
    
    return $errors;
}

// Initialize application
function initializeApp() {
    // Start session
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Set security headers
    setSecurityHeaders();
    
    // Validate configuration
    $configErrors = validateConfiguration();
    if (!empty($configErrors) && DEBUG_MODE) {
        foreach ($configErrors as $error) {
            error_log("Configuration error: $error");
        }
    }
    
    // Log application start
    logActivity('APP_START', 'Application initialized');
}

// Auto-initialize if this file is included
if (!defined('CCST_LABS_INITIALIZED')) {
    define('CCST_LABS_INITIALIZED', true);
    initializeApp();
}
?> 