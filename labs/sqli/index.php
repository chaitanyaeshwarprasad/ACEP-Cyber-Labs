<?php
session_start();

// Initialize progress tracking
if (!isset($_SESSION['progress'])) {
    $_SESSION['progress'] = [
        'sqli' => 0,
        'xss' => 0,
        'csrf' => 0,
        'auth' => 0,
        'upload' => 0,
        'session' => 0
    ];
}

$message = '';
$users = [];
$completion_message = '';
$query_log = [];
$error_message = '';

// Handle completion
if (isset($_POST['complete_lab'])) {
    $_SESSION['progress']['sqli'] = 100;
    $completion_message = "🎉 Congratulations! SQL Injection Lab completed!";
}

// Create database directory if it doesn't exist
$db_dir = '/tmp/acep_labs/';
if (!is_dir($db_dir)) {
    mkdir($db_dir, 0777, true);
}

// Initialize database
try {
    $db = new SQLite3($db_dir . 'sqli_lab.db');
    
    // Create table if not exists
    $db->exec("CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY, username TEXT, password TEXT, email TEXT, role TEXT)");
    
    // Insert sample data only if table is empty
    $result = $db->query("SELECT COUNT(*) as count FROM users");
    $count = $result->fetchArray()['count'];
    
    if ($count == 0) {
        $db->exec("INSERT INTO users (id, username, password, email, role) VALUES (1, 'admin', 'admin123', 'admin@acep.com', 'admin')");
        $db->exec("INSERT INTO users (id, username, password, email, role) VALUES (2, 'user1', 'password123', 'user1@acep.com', 'user')");
        $db->exec("INSERT INTO users (id, username, password, email, role) VALUES (3, 'user2', 'password456', 'user2@acep.com', 'user')");
        $db->exec("INSERT INTO users (id, username, password, email, role) VALUES (4, 'test', 'test123', 'test@acep.com', 'user')");
    }
} catch (Exception $e) {
    $error_message = "Database error: " . $e->getMessage();
}

if ($_POST && !$error_message) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // VULNERABLE: Direct SQL injection
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $query_log[] = $query;
    
    try {
        $result = $db->query($query);
        
        if ($result) {
            $user = $result->fetchArray();
            if ($user) {
                $_SESSION['user'] = $user;
                $message = "Login successful! Welcome " . htmlspecialchars($user['username']);
                
                // Auto-complete if admin login achieved
                if ($user['username'] === 'admin') {
                    $_SESSION['progress']['sqli'] = 100;
                    $completion_message = "🎉 SQL Injection successful! Lab completed!";
                }
            } else {
                $message = "Invalid credentials";
            }
        }
    } catch (Exception $e) {
        $message = "Query error: " . $e->getMessage();
    }
}

// Show all users (vulnerable)
if (isset($_GET['show_users']) && !$error_message) {
    $query = "SELECT * FROM users";
    $query_log[] = $query;
    
    try {
        $result = $db->query($query);
        while ($row = $result->fetchArray()) {
            $users[] = $row;
        }
    } catch (Exception $e) {
        $message = "Query error: " . $e->getMessage();
    }
}

$progress = $_SESSION['progress']['sqli'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Injection Lab - ACEP CYBER LABS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 50%, #16213e 100%);
            color: #ffffff;
            min-height: 100vh;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            padding: 40px 0;
            background: rgba(0, 0, 0, 0.4);
            border-radius: 20px;
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        
        .lab-title {
            font-size: 2.5rem;
            font-weight: bold;
            background: linear-gradient(45deg, #00BFFF, #0099cc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }
        
        .lab-subtitle {
            font-size: 1.1rem;
            color: #00BFFF;
            margin-bottom: 15px;
        }
        
        .progress-section {
            background: rgba(0, 0, 0, 0.3);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .progress-bar {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            height: 25px;
            margin: 15px 0;
            overflow: hidden;
            position: relative;
        }
        
        .progress-fill {
            background: linear-gradient(45deg, #00BFFF, #0099cc);
            height: 100%;
            border-radius: 15px;
            transition: width 0.8s ease;
            position: relative;
        }
        
        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shimmer 2s infinite;
        }
        
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
        
        .main-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .lab-section {
            background: rgba(0, 0, 0, 0.4);
            padding: 30px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: #00BFFF;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-group {
            margin: 20px 0;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #cccccc;
        }
        
        .form-input {
            width: 100%;
            padding: 15px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            background: rgba(0, 0, 0, 0.3);
            color: #ffffff;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #00BFFF;
        }
        
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            font-size: 1rem;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, #00BFFF, #0099cc);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
        }
        
        .btn-success {
            background: linear-gradient(45deg, #2ed573, #1e90ff);
            color: white;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(46, 213, 115, 0.4);
        }
        
        .message {
            padding: 20px;
            margin: 20px 0;
            border-radius: 15px;
            font-weight: 600;
        }
        
        .message.success {
            background: linear-gradient(45deg, #2ed573, #1e90ff);
            color: white;
        }
        
        .message.error {
            background: linear-gradient(45deg, #ff4757, #ff3742);
            color: white;
        }
        
        .completion {
            background: linear-gradient(45deg, #ffa502, #ff9500);
            color: black;
            padding: 20px;
            margin: 20px 0;
            border-radius: 15px;
            text-align: center;
            font-weight: bold;
            font-size: 1.1rem;
        }
        
        .hint-box {
            background: linear-gradient(45deg, #ffa502, #ff9500);
            color: black;
            padding: 20px;
            border-radius: 15px;
            margin: 20px 0;
        }
        
        .hint-title {
            font-weight: bold;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }
        
        .hint-list {
            list-style: none;
        }
        
        .hint-list li {
            margin: 10px 0;
            padding: 10px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }
        
        .user-list {
            margin-top: 20px;
        }
        
        .user-item {
            padding: 15px;
            margin: 10px 0;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .query-log {
            background: rgba(0, 0, 0, 0.5);
            padding: 15px;
            border-radius: 10px;
            margin: 15px 0;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            color: #00BFFF;
            border: 1px solid rgba(0, 191, 255, 0.3);
        }
        
        .complete-section {
            text-align: center;
            margin: 30px 0;
        }
        
        .complete-btn {
            background: linear-gradient(45deg, #2ed573, #1e90ff);
            color: white;
            border: none;
            padding: 20px 40px;
            border-radius: 30px;
            font-weight: bold;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .complete-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(46, 213, 115, 0.4);
        }
        

        
        .error-box {
            background: linear-gradient(45deg, #ff4757, #ff3742);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin: 20px 0;
            text-align: center;
        }
        
        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
            }
            
            .lab-title {
                font-size: 2rem;
            }
            
            .container {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="lab-title">
                <i class="fas fa-database"></i> SQL Injection Lab
            </div>
            <div class="lab-subtitle">Master Database Manipulation Attacks</div>
            <p>Practice SQL injection attacks on a vulnerable web application</p>
        </div>
        
        <?php if ($error_message): ?>
            <div class="error-box">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>System Error:</strong> <?= htmlspecialchars($error_message) ?>
                <br><br>
                <small>Please ensure SQLite3 is installed and the /tmp directory is writable.</small>
            </div>
        <?php endif; ?>
        
        <div class="progress-section">
            <h3><i class="fas fa-chart-line"></i> Your Progress</h3>
            <div class="progress-bar">
                <div class="progress-fill" style="width: <?= $progress ?>%"></div>
            </div>
            <p><strong><?= $progress ?>%</strong> Complete</p>
        </div>
        
        <div class="main-content">
            <div class="lab-section">
                <div class="section-title">
                    <i class="fas fa-sign-in-alt"></i> Login Form
                </div>
                
                <div class="hint-box">
                    <div class="hint-title">💡 SQL Injection Hints:</div>
                    <ul class="hint-list">
                        <li><code>' OR '1'='1</code> - Bypass authentication</li>
                        <li><code>' UNION SELECT 1,2,3,4,5--</code> - Test union injection</li>
                        <li><code>' UNION SELECT username,password,email,role,id FROM users--</code> - Extract data</li>
                        <li><code>' OR 1=1--</code> - Alternative bypass</li>
                        <li><code>' OR username='admin'--</code> - Target specific user</li>
                    </ul>
                </div>
                
                <form method="POST">
                    <div class="form-group">
                        <label class="form-label">Username:</label>
                        <input type="text" name="username" class="form-input" placeholder="Enter username or SQL injection payload">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password:</label>
                        <input type="password" name="password" class="form-input" placeholder="Enter password or SQL injection payload">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>
                </form>
                
                <?php if ($message): ?>
                    <div class="message <?= strpos($message, 'successful') !== false ? 'success' : 'error' ?>">
                        <i class="fas fa-<?= strpos($message, 'successful') !== false ? 'check-circle' : 'exclamation-circle' ?>"></i>
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($completion_message): ?>
                    <div class="completion">
                        <i class="fas fa-trophy"></i> <?= htmlspecialchars($completion_message) ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="lab-section">
                <div class="section-title">
                    <i class="fas fa-users"></i> Database Operations
                </div>
                
                <div class="form-group">
                    <a href="?show_users=1" class="btn btn-primary">
                        <i class="fas fa-database"></i> Show All Users
                    </a>
                </div>
                
                <?php if ($query_log): ?>
                    <div class="query-log">
                        <strong>Last Query:</strong><br>
                        <?= htmlspecialchars(end($query_log)) ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($users): ?>
                    <div class="user-list">
                        <h4><i class="fas fa-list"></i> All Users:</h4>
                        <?php foreach ($users as $user): ?>
                            <div class="user-item">
                                <strong>ID:</strong> <?= htmlspecialchars($user['id']) ?> | 
                                <strong>Username:</strong> <?= htmlspecialchars($user['username']) ?> | 
                                <strong>Email:</strong> <?= htmlspecialchars($user['email']) ?> | 
                                <strong>Role:</strong> <?= htmlspecialchars($user['role']) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($progress < 100): ?>
                    <div class="complete-section">
                        <form method="POST">
                            <button type="submit" name="complete_lab" class="complete-btn">
                                <i class="fas fa-check-circle"></i> Mark Lab as Complete
                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="completion">
                        <i class="fas fa-star"></i> ✅ Lab Completed! You can now move to the next lab.
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="back-link">
            <a href="../../index.php">
                <i class="fas fa-arrow-left"></i> Back to Labs Dashboard
            </a>
        </div>
    </div>
    
    <script>
        // Add smooth animations
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.lab-section, .header, .progress-section');
            elements.forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    el.style.transition = 'all 0.6s ease';
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
        
        // Add form validation and enhancement
        const form = document.querySelector('form');
        const inputs = document.querySelectorAll('.form-input');
        
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
        
        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'Enter') {
                form.submit();
            }
        });
    </script>

</body>
</html>