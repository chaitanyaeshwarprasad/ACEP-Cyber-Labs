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
$uploaded_files = $_SESSION['uploaded_files'] ?? [];

// Handle completion
if (isset($_POST['complete_lab'])) {
    $_SESSION['progress']['upload'] = 100;
    $completion_message = "🎉 Congratulations! File Upload Lab completed!";
}

if ($_POST && isset($_FILES['file']) && !isset($_POST['complete_lab'])) {
    $file = $_FILES['file'];
    $filename = $file['name'];
    $tmp_name = $file['tmp_name'];
    
    // VULNERABLE: Weak file validation
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'txt'];
    $file_extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    if (in_array($file_extension, $allowed_extensions)) {
        // Create upload directory
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $upload_path = $upload_dir . $filename;
        
        if (move_uploaded_file($tmp_name, $upload_path)) {
            $uploaded_files[] = [
                'name' => $filename,
                'path' => $upload_path,
                'time' => date('Y-m-d H:i:s')
            ];
            $_SESSION['uploaded_files'] = $uploaded_files;
            $message = "File uploaded successfully!";
            
            // Auto-complete if malicious file detected
            if (strpos($filename, '.php') !== false || strpos($filename, '.php.') !== false) {
                $_SESSION['progress']['upload'] = 100;
                $completion_message = "🎉 File Upload Bypass detected! Lab completed!";
            }
        } else {
            $message = "Upload failed!";
        }
    } else {
        $message = "Invalid file type! Allowed: " . implode(', ', $allowed_extensions);
    }
}

$progress = $_SESSION['progress']['upload'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload Lab - ACEP CYBER LABS</title>
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
        }
        .message.success {
            background: linear-gradient(45deg, var(--neon-green), #1e90ff);
            color: white;
        }
        .message.error {
            background: linear-gradient(45deg, #ff4757, #ff3742);
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
        .file-list {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 15px;
            margin: 20px 0;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .file-item {
            padding: 15px;
            margin: 10px 0;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .file-item strong {
            color: var(--neon-blue);
        }
        .file-item small {
            color: var(--text-secondary);
        }
        .file-item a {
            color: var(--neon-green);
            text-decoration: none;
        }
        .file-item a:hover {
            text-decoration: underline;
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
                <i class="fas fa-upload"></i> File Upload Lab
            </div>
            <div class="lab-subtitle">Master File Upload Vulnerabilities</div>
            <p>Practice file upload bypass techniques and restrictions</p>
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
                    <i class="fas fa-cloud-upload-alt"></i> File Upload System
                </div>
                
                <div class="hint-box">
                    <div class="hint-title">💡 Upload Bypass Techniques:</div>
                    <ul class="hint-list">
                        <li><strong>Double Extension:</strong> shell.php.jpg</li>
                        <li><strong>Case Manipulation:</strong> shell.PHP</li>
                        <li><strong>Null Byte:</strong> shell.php%00.jpg</li>
                        <li><strong>MIME Type Bypass:</strong> Change Content-Type header</li>
                        <li><strong>Magic Bytes:</strong> Add image headers to PHP files</li>
                    </ul>
                </div>
                
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="form-label">Select File:</label>
                        <input type="file" name="file" accept=".jpg,.jpeg,.png,.gif,.txt" class="form-input">
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Upload File
                    </button>
                </form>
                
                <?php if ($message): ?>
                    <div class="message <?= strpos($message, 'successfully') !== false ? 'success' : 'error' ?>">
                        <i class="fas fa-<?= strpos($message, 'successfully') !== false ? 'check-circle' : 'exclamation-circle' ?>"></i>
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
                    <i class="fas fa-folder-open"></i> Uploaded Files
                </div>
                
                <div class="file-list">
                    <h4><i class="fas fa-list"></i> Recent Uploads:</h4>
                    <?php if (empty($uploaded_files)): ?>
                        <p style="color: var(--text-secondary); text-align: center; padding: 20px;">
                            No files uploaded yet.
                        </p>
                    <?php else: ?>
                        <?php foreach ($uploaded_files as $file): ?>
                            <div class="file-item">
                                <strong><?= htmlspecialchars($file['name']) ?></strong><br>
                                <small>Uploaded: <?= $file['time'] ?></small><br>
                                <a href="<?= $file['path'] ?>" target="_blank">
                                    <i class="fas fa-external-link-alt"></i> View File
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
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