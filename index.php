<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACEP CYBER LABS - Professional Cybersecurity Lab Platform</title>
    <link rel="stylesheet" href="assets/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="dark-mode">
    <div class="app-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <div class="title-row">
                        <i class="fas fa-shield-alt"></i>
                        <h1>ACEP CYBER LABS</h1>
                    </div>

                </div>
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <h3>Categories</h3>
                    <ul>
                        <li>
                            <a href="#critical" data-filter="critical">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>Critical</span>
                                <span class="badge">2</span>
                            </a>
                        </li>
                        <li>
                            <a href="#high" data-filter="high">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>High</span>
                                <span class="badge">3</span>
                            </a>
                        </li>
                        <li>
                            <a href="#medium" data-filter="medium">
                                <i class="fas fa-info-circle"></i>
                                <span>Medium</span>
                                <span class="badge">1</span>
                            </a>
                        </li>
                        <li>
                            <a href="#owasp" data-filter="owasp">
                                <i class="fas fa-shield-alt"></i>
                                <span>OWASP Top 10</span>
                                <span class="badge">4</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                
            </nav>
            
            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-details">
                        <span class="user-name">Student</span>
                        <span class="user-role">Security Learner</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="top-header">
                <div class="header-left">
                    <button class="menu-toggle" id="menuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="breadcrumb">
                        <span>Dashboard</span>
                    </div>
                </div>
                
                <div class="header-right">
                    <div class="search-container">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" placeholder="Search modules..." />
                    </div>
                    
                    <div class="header-controls">
                        <button class="btn-notification" id="notificationBtn">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge">3</span>
                        </button>
                        <button id="darkModeToggle" class="btn-toggle">
                            <i class="fas fa-moon"></i>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- Welcome Section -->
                <section class="welcome-section">
                    <div class="welcome-content">
                        <h2>Welcome back, Security Learner!</h2>
                        <p>Continue your journey in web application security. You have <strong id="remainingCount">...</strong> modules remaining to complete.</p> <!-- JS will update this value dynamically -->
                    </div>
                    <div class="welcome-actions">
                        <button class="btn-primary" id="resumeBtn">
                            <i class="fas fa-play"></i>
                            Resume Learning
                        </button>
                        <button class="btn-secondary" id="randomBtn">
                            <i class="fas fa-random"></i>
                            Random Module
                        </button>
                    </div>
                </section>

                <!-- Stats Cards -->
                <section class="stats-section">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="stat-content">
                                <h3 id="completedModules">0</h3>
                                <p>Completed</p>
                            </div>
                            <div class="stat-progress">
                                <div class="progress-circle" data-progress="0">
                                    <svg viewBox="0 0 36 36">
                                        <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                        <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-content">
                                <h3 id="totalScore">0</h3>
                                <p>Total Score</p>
                            </div>
                            <div class="stat-trend">
                                <i class="fas fa-arrow-up"></i>
                                <span>+12%</span>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-fire"></i>
                            </div>
                            <div class="stat-content">
                                <h3 id="streakDays">0</h3>
                                <p>Day Streak</p>
                            </div>
                            <div class="stat-trend">
                                <i class="fas fa-arrow-up"></i>
                                <span>+2 days</span>
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="stat-content">
                                <h3 id="achievements">0</h3>
                                <p>Achievements</p>
                            </div>
                            <div class="stat-badge">
                                <i class="fas fa-medal"></i>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Progress Overview -->
                <section class="progress-section">
                    <div class="section-header">
                        <h3>Learning Progress</h3>
                        <div class="progress-stats">
                            <span id="progressPercentage">0%</span>
                            <span>Complete</span>
                        </div>
                    </div>
                    
                    <div class="progress-container">
                        <div class="progress-bar-container">
                            <div class="progress-bar" id="progressBar" style="width: 0%"></div>
                        </div>
                        <div class="progress-labels">
                            <span>0%</span>
                            <span>25%</span>
                            <span>50%</span>
                            <span>75%</span>
                            <span>100%</span>
                        </div>
                    </div>
                </section>

                <!-- Recent Activity -->
                <section class="activity-section">
                    <div class="section-header">
                        <h3>Recent Activity</h3>
                        <button class="btn-text">View All</button>
                    </div>
                    
                    <div class="activity-list" id="activityList">
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="activity-content">
                                <h4>SQL Injection Module</h4>
                                <p>Completed with 95% score</p>
                                <span class="activity-time">2 hours ago</span>
                            </div>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-play-circle"></i>
                            </div>
                            <div class="activity-content">
                                <h4>XSS Module</h4>
                                <p>Started learning</p>
                                <span class="activity-time">1 day ago</span>
                            </div>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="activity-content">
                                <h4>Achievement Unlocked</h4>
                                <p>First Module Complete</p>
                                <span class="activity-time">3 days ago</span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Modules Grid -->
                <section class="modules-section">
                    <div class="section-header">
                        <h3>Security Modules</h3>
                        <div class="filter-controls">
                            <button class="filter-btn active" data-filter="all">All</button>
                            <button class="filter-btn" data-filter="critical">Critical</button>
                            <button class="filter-btn" data-filter="high">High</button>
                            <button class="filter-btn" data-filter="medium">Medium</button>
                            <button class="filter-btn" data-filter="owasp">OWASP Top 10</button>
                        </div>
                    </div>

                    <div class="modules-grid" id="modulesGrid">
                        <!-- Module cards will be populated here -->
                    </div>
                </section>
            </div>
        </main>
    </div>

    <!-- Notification Panel -->
    <div class="notification-panel" id="notificationPanel">
        <div class="notification-header">
            <h3>Notifications</h3>
            <button class="btn-close" id="closeNotifications">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="notification-list" id="notificationList">
            <!-- Notifications will be populated here -->
        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>

    <!-- Footer -->
    <footer class="app-footer">
        <div class="footer-content">
            <div class="creator">Created by A Chaitanya Eshwar Prasad</div>
            <div class="social-links">
                <a href="https://chaitanyaeshwarprasad.com" target="_blank" rel="noopener" aria-label="Website"><i class="fas fa-globe"></i></a>
                <a href="https://instagram.com/acep.tech.in.telugu" target="_blank" rel="noopener" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="https://linkedin.com/in/chaitanya-eshwar-prasad" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>

                <a href="https://yeswehack.com/hunters/chaitanya-eshwar-prasad#latest-hacktivity" target="_blank" rel="noopener" aria-label="YesWeHack"><i class="fas fa-user-secret"></i></a>
                <a href="https://youtube.com/@chaitanya.eshwar.prasad/videos" target="_blank" rel="noopener" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                <a href="https://github.com/chaitanyaeshwarprasad" target="_blank" rel="noopener" aria-label="GitHub"><i class="fab fa-github"></i></a>
            </div>
        </div>
    </footer>
    <script src="assets/script.js"></script>
</body>
</html> 