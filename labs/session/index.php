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
$completion_message = '';

// Initialize session data
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 'user_' . rand(1000, 9999);
    $_SESSION['username'] = 'demo_user';
    $_SESSION['role'] = 'user';
    $_SESSION['balance'] = 1000;
}

// Handle completion
if (isset($_POST['complete_lab'])) {
    $_SESSION['progress']['session'] = 100;
    $completion_message = "🎉 Congratulations! Session Management Lab completed!";
}

// VULNERABLE: Session fixation
if (isset($_GET['session_id'])) {
    session_id($_GET['session_id']);
    session_start();
    $message = "Session ID changed to: " . session_id();
    
    // Auto-complete if admin session detected
    if (strpos($_GET['session_id'], 'admin') !== false) {
        $_SESSION['progress']['session'] = 100;
        $completion_message = "🎉 Session Hijacking Successful! Lab completed!";
    }
}

// VULNERABLE: Session prediction
if (isset($_GET['predict'])) {
    $predicted_id = 'user_' . $_GET['predict'];
    session_id($predicted_id);
    session_start();
    $message = "Session ID changed to: " . session_id();
}

// VULNERABLE: Session hijacking
if (isset($_GET['hijack'])) {
    $hijacked_id = $_GET['hijack'];
    session_id($hijacked_id);
    session_start();
    $message = "Session hijacked! ID: " . session_id();
    
    // Auto-complete if successful hijacking
    if ($hijacked_id !== session_id()) {
        $_SESSION['progress']['session'] = 100;
        $completion_message = "🎉 Session Hijacking Successful! Lab completed!";
    }
}

// Display session info
$session_info = [
    'Session ID' => session_id(),
    'User ID' => $_SESSION['user_id'],
    'Username' => $_SESSION['username'],
    'Role' => $_SESSION['role'],
    'Balance' => '$' . $_SESSION['balance']
];

$progress = $_SESSION['progress']['session'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Management Lab - ACEP CYBER LABS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/style.css">
    <style>
        :root {
            --neon-blue: #00BFFF;
            --neon-green: #28a745;
            --neon-yellow: #ffc107;
            --dark-bg: #0a0a0a;
            --card-bg: #181a1b;
            --text-primary: #fff;
            --text-secondary: #b0b0b0;
            --border-color: #333;

            --gradient-primary: linear-gradient(135deg, var(--neon-blue) 0%, #0099cc 100%);
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--dark-bg) 0%, #050505 100%);
            color: var(--text-primary);
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
        }
        .lab-title {
            font-size: 2.5rem;
            font-weight: bold;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }
        .lab-subtitle {
            font-size: 1.1rem;
            color: var(--neon-blue);
            margin-bottom: 15px;
        }
        .progress-section {
            background: var(--card-bg);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
            border: 1px solid var(--border-color);
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
            background: var(--gradient-primary);
            height: 100%;
            border-radius: 15px;
            transition: width 0.8s ease;
            position: relative;
        }
        .main-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        .lab-section {
            background: var(--card-bg);
            padding: 30px;
            border-radius: 20px;
            border: 1px solid var(--border-color);
            backdrop-filter: blur(10px);
        }
        .section-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: var(--neon-blue);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .session-info {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 15px;
            margin: 20px 0;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .info-item {
            margin: 15px 0;
            padding: 10px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
        }
        .info-label {
            font-weight: bold;
            color: var(--neon-blue);
        }
        .hint-box {
            background: linear-gradient(45deg, var(--neon-yellow), #ff9500);
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
            font-weight: bold;
        }
        .attack-box {
            background: linear-gradient(45deg, #ff4757, #ff3742);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin: 20px 0;
        }
        .attack-box h4 {
            margin-bottom: 15px;
            color: var(--neon-yellow);
        }
        .attack-box h5 {
            margin: 15px 0 10px 0;
            color: var(--neon-yellow);
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            font-size: 0.9rem;
            margin: 5px;
            background: var(--gradient-primary);
            color: white;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
        .message {
            padding: 20px;
            margin: 20px 0;
            border-radius: 15px;
            font-weight: 600;
            background: linear-gradient(45deg, var(--neon-green), #1e90ff);
            color: white;
        }
        .completion {
            background: linear-gradient(45deg, var(--neon-yellow), #ff9500);
            color: black;
            padding: 20px;
            margin: 20px 0;
            border-radius: 15px;
            text-align: center;
            font-weight: bold;
            font-size: 1.1rem;
        }

        
        .complete-section {
            margin-top: 20px;
            text-align: center;
        }
        
        .complete-btn {
            background: linear-gradient(45deg, var(--neon-green), #1e90ff);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
        }
        
        .complete-btn:hover {
            transform: translateY(-2px);
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
                <i class="fas fa-cookie-bite"></i> Session Management Lab
            </div>
            <div class="lab-subtitle">Master Session Hijacking & Management</div>
            <p>Practice session hijacking, fixation, and prediction attacks</p>
        </div>
        
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
                    <i class="fas fa-user-shield"></i> Session Information
                </div>
                
                <div class="session-info">
                    <h3><i class="fas fa-info-circle"></i> Current Session:</h3>
                    <?php foreach ($session_info as $label => $value): ?>
                        <div class="info-item">
                            <span class="info-label"><?= $label ?>:</span> <?= htmlspecialchars($value) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if ($message): ?>
                    <div class="message">
                        <i class="fas fa-check-circle"></i> <?= htmlspecialchars($message) ?>
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
                    <i class="fas fa-bug"></i> Session Attack Guide
                </div>
                
                <div class="hint-box">
                    <div class="hint-title">💡 Session Attack Techniques:</div>
                    <ul class="hint-list">
                        <li><strong>Session Fixation:</strong> Set a known session ID</li>
                        <li><strong>Session Prediction:</strong> Guess predictable session IDs</li>
                        <li><strong>Session Hijacking:</strong> Use another user's session ID</li>
                        <li><strong>Session Enumeration:</strong> Try different session IDs</li>
                    </ul>
                </div>
                
                <div class="attack-box">
                    <h4><i class="fas fa-exclamation-triangle"></i> Session Attacks:</h4>
                    
                    <h5><i class="fas fa-link"></i> Session Fixation:</h5>
                    <p>Try to set a specific session ID:</p>
                    <a href="?session_id=attacker_session" class="btn">Set Session ID: attacker_session</a>
                    <a href="?session_id=admin_session" class="btn">Set Session ID: admin_session</a>
                    
                    <h5><i class="fas fa-crystal-ball"></i> Session Prediction:</h5>
                    <p>Try to predict session IDs:</p>
                    <a href="?predict=1000" class="btn">Predict: user_1000</a>
                    <a href="?predict=2000" class="btn">Predict: user_2000</a>
                    <a href="?predict=3000" class="btn">Predict: user_3000</a>
                    
                    <h5><i class="fas fa-user-secret"></i> Session Hijacking:</h5>
                    <p>Try to hijack sessions:</p>
                    <a href="?hijack=user_1234" class="btn">Hijack: user_1234</a>
                    <a href="?hijack=admin_9999" class="btn">Hijack: admin_9999</a>
                    <a href="?hijack=root_session" class="btn">Hijack: root_session</a>
                </div>
                
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

</body>
</html> 