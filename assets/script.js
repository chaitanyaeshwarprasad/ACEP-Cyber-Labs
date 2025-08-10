// CCST Labs - Modern Dashboard JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize the application
    initializeApp();
});

function initializeApp() {
    // Performance monitoring
    const startTime = performance.now();
    
    try {
        // Initialize all components
        initializeSidebar();
        initializeDarkMode();
        initializeSearchAndFilter();
        initializeProgressTracking();
        initializeModuleCards();
        initializeNotifications();
        initializeKeyboardNavigation();
        initializeAccessibility();
        initializeStats();
        initializeActivityFeed();
        
        // Show welcome notification
        setTimeout(() => {
            showNotification('Welcome to ACEP CYBER LABS! Start exploring vulnerabilities to begin your learning journey.', 'info');
        }, 1000);
        
        // Log performance metrics
        const endTime = performance.now();
        console.log(`App initialization completed in ${(endTime - startTime).toFixed(2)}ms`);
        
        // Add loaded class to body
        document.body.classList.add('loaded');
        
    } catch (error) {
        console.error('Error initializing app:', error);
        showNotification('Failed to initialize application. Please refresh the page.', 'error');
    }
}

// Sidebar functionality
function initializeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const menuToggle = document.getElementById('menuToggle');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('overlay');
    
    // Mobile menu toggle
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });
    }
    
    // Sidebar toggle button
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
    }
    
    // Close sidebar when clicking overlay
    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    }
    
    // Handle sidebar navigation
    const navLinks = document.querySelectorAll('.sidebar-nav a');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const filter = this.dataset.filter;
            if (filter) {
                e.preventDefault();
                filterModules('', filter);
                updateActiveNav(this);
            }
        });
    });
}

// Dark mode functionality
function initializeDarkMode() {
    const darkModeToggle = document.getElementById('darkModeToggle');
    if (!darkModeToggle) return;
    
    const body = document.body;
    const icon = darkModeToggle.querySelector('i');
    
    // Check for saved dark mode preference
    const savedMode = localStorage.getItem('darkMode');
    if (savedMode === 'light') {
        body.classList.remove('dark-mode');
        icon.classList.remove('fa-sun');
        icon.classList.add('fa-moon');
    }
    
    // Dark mode toggle event
    darkModeToggle.addEventListener('click', function() {
        body.classList.toggle('dark-mode');
        
        if (body.classList.contains('dark-mode')) {
            icon.classList.remove('fa-sun');
            icon.classList.add('fa-moon');
            localStorage.setItem('darkMode', 'dark');
            showNotification('Dark mode enabled', 'info');
        } else {
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
            localStorage.setItem('darkMode', 'light');
            showNotification('Light mode enabled', 'info');
        }
    });
}

// Search and filter functionality
function initializeSearchAndFilter() {
    const searchInput = document.getElementById('searchInput');
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    // Search functionality
    if (searchInput) {
        searchInput.addEventListener('input', debounce(function() {
            const searchTerm = this.value.toLowerCase();
            const activeFilter = document.querySelector('.filter-btn.active')?.dataset.filter || 'all';
            filterModules(searchTerm, activeFilter);
        }, 300));
    }
    
    // Filter functionality
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
            const filter = this.dataset.filter;
            
            filterModules(searchTerm, filter);
        });
    });
}

function filterModules(searchTerm, filter) {
    const moduleCards = document.querySelectorAll('.module-card');
    let visibleCount = 0;
    
    moduleCards.forEach(card => {
        const title = card.querySelector('h3')?.textContent.toLowerCase() || '';
        const description = card.querySelector('p')?.textContent.toLowerCase() || '';
        const severity = card.dataset.severity;
        const isOwasp = card.dataset.owasp === 'true';
        
        let matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);
        let matchesFilter = filter === 'all' || 
                          (filter === 'critical' && severity === 'critical') ||
                          (filter === 'high' && severity === 'high') ||
                          (filter === 'medium' && severity === 'medium') ||
                          (filter === 'owasp' && isOwasp);
        
        if (matchesSearch && matchesFilter) {
            card.style.display = 'block';
            card.style.animation = 'fadeInUp 0.3s ease';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    updateModuleCount(visibleCount);
}

function updateModuleCount(visibleCount) {
    const totalModules = getTotalModules();
    
    if (visibleCount === 0) {
        showNotification('No modules match your search criteria', 'warning');
    }
    
    // Update remaining count
    const remainingCount = document.getElementById('remainingCount');
    if (remainingCount) {
        remainingCount.textContent = totalModules - getCompletedModules();
    }
}

// Progress tracking functionality
function initializeProgressTracking() {
    const progressBar = document.getElementById('progressBar');
    const progressPercentage = document.getElementById('progressPercentage');
    
    function updateProgress() {
        const completedModules = getCompletedModules();
        const totalModules = getTotalModules();
        const progress = totalModules === 0 ? 0 : Math.round((completedModules / totalModules) * 100);
        
        // Update progress bar
        if (progressBar) {
            progressBar.style.width = progress + '%';
        }
        if (progressPercentage) {
            progressPercentage.textContent = progress + '%';
        }
        
        // Update progress circle
        updateProgressCircle(progress);
        
        // Add completion animation
        if (progress === 100) {
            showNotification('Congratulations! You have completed all modules!', 'success');
        }
    }
    
    // Update progress on page load
    updateProgress();
    
    // Expose update function globally
    window.updateProgress = updateProgress;
}

function updateProgressCircle(progress) {
    const progressCircle = document.querySelector('.progress-circle');
    if (progressCircle) {
        const path = progressCircle.querySelector('path:last-child');
        if (path) {
            const circumference = 100; // Simplified for demo
            const offset = circumference - (progress / 100) * circumference;
            path.style.strokeDashoffset = offset;
        }
    }
}

function getCompletedModules() {
    try {
        const completedModules = localStorage.getItem('completedModules') || '[]';
        return JSON.parse(completedModules).length;
    } catch (error) {
        console.error('Error getting completed modules:', error);
        return 0;
    }
}

function calculateTotalScore() {
    try {
        const completedModules = JSON.parse(localStorage.getItem('completedModules') || '[]');
        let totalScore = 0;
        
        completedModules.forEach(module => {
            totalScore += module.score || 0;
        });
        
        return totalScore;
    } catch (error) {
        console.error('Error calculating total score:', error);
        return 0;
    }
}

// Module cards functionality
function initializeModuleCards() {
    const moduleCards = document.querySelectorAll('.module-card');
    
    // Populate modules grid if empty
    if (moduleCards.length === 0) {
        populateModulesGrid();
    }
    
    moduleCards.forEach(card => {
        // Add click event
        card.addEventListener('click', function() {
            const moduleName = this.dataset.module;
            if (moduleName) {
                navigateToModule(moduleName);
            }
        });
        
        // Add hover effects
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px) scale(1.006)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
        
        // Add keyboard navigation
        card.setAttribute('tabindex', '0');
        card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
        
        // Check completion status
        checkModuleCompletion(card);
    });
}

function populateModulesGrid() {
    const modulesGrid = document.getElementById('modulesGrid');
    if (!modulesGrid) return;
    
    const modules = [
        {
            name: 'sqli',
            title: 'SQL Injection',
            description: 'Learn to prevent unauthorized database access through malicious SQL queries.',
            icon: 'fas fa-database',
            severity: 'critical',
            owasp: true,
            tags: ['critical', 'owasp']
        },
        {
            name: 'xss',
            title: 'Cross-Site Scripting',
            description: 'Understand how malicious scripts can be executed through user input.',
            icon: 'fas fa-code',
            severity: 'high',
            owasp: true,
            tags: ['high', 'owasp']
        },
        {
            name: 'csrf',
            title: 'CSRF',
            description: 'Learn about Cross-Site Request Forgery and token-based protection.',
            icon: 'fas fa-exchange-alt',
            severity: 'medium',
            owasp: true,
            tags: ['medium', 'owasp']
        },
        {
            name: 'auth',
            title: 'Authentication',
            description: 'Learn secure authentication practices and session management.',
            icon: 'fas fa-key',
            severity: 'critical',
            owasp: true,
            tags: ['critical', 'owasp']
        },
        {
            name: 'upload',
            title: 'File Upload',
            description: 'Understand how to secure file upload functionality against malicious files.',
            icon: 'fas fa-upload',
            severity: 'high',
            owasp: false,
            tags: ['high', 'file']
        },
        {
            name: 'session',
            title: 'Session Management',
            description: 'Explore session security and prevention of session-based attacks.',
            icon: 'fas fa-user-secret',
            severity: 'high',
            owasp: false,
            tags: ['high', 'session']
        }
    ];
    
    modules.forEach(module => {
        const card = createModuleCard(module);
        modulesGrid.appendChild(card);
    });
}

function createModuleCard(module) {
    const card = document.createElement('div');
    card.className = 'module-card';
    card.dataset.module = module.name;
    card.dataset.severity = module.severity;
    card.dataset.owasp = module.owasp;
    
    card.innerHTML = `
        <div class="module-icon">
            <i class="${module.icon}"></i>
        </div>
        <h3>${module.title}</h3>
        <p>${module.description}</p>
        <div class="module-tags">
            ${module.tags.map(tag => `<span class="tag ${tag}">${tag.charAt(0).toUpperCase() + tag.slice(1)}</span>`).join('')}
        </div>
        <div style="margin-top:14px;display:flex;justify-content:flex-end;">
            <a href="labs/${module.name}/" class="btn-secondary" aria-label="Go to ${module.title} lab">Go to Lab</a>
        </div>
    `;
    
    return card;
}

function navigateToModule(moduleName) {
    try {
        const modulePath = `labs/${moduleName}/`;
        
        // Mark as visited
        markModuleAsVisited(moduleName);
        
        // Navigate to module
        window.location.href = modulePath;
    } catch (error) {
        console.error('Error navigating to module:', error);
        showNotification('Failed to navigate to module. Please try again.', 'error');
    }
}

function markModuleAsVisited(moduleName) {
    try {
        const visitedModules = JSON.parse(localStorage.getItem('visitedModules') || '[]');
        if (!visitedModules.includes(moduleName)) {
            visitedModules.push(moduleName);
            localStorage.setItem('visitedModules', JSON.stringify(visitedModules));
        }
    } catch (error) {
        console.error('Error marking module as visited:', error);
    }
}

function checkModuleCompletion(card) {
    try {
        const moduleName = card.dataset.module;
        const completedModules = JSON.parse(localStorage.getItem('completedModules') || '[]');
        const isCompleted = completedModules.some(module => module.name === moduleName);
        
        if (isCompleted) {
            card.classList.add('completed');
            const badge = document.createElement('span');
            badge.className = 'completion-badge';
            badge.innerHTML = '<i class="fas fa-check"></i>';
            card.appendChild(badge);
        }
    } catch (error) {
        console.error('Error checking module completion:', error);
    }
}

// Stats functionality
function initializeStats() {
    updateStats();
    
    // Resume learning button
    const resumeBtn = document.getElementById('resumeBtn');
    if (resumeBtn) {
        resumeBtn.addEventListener('click', function() {
            const lastVisited = getLastVisitedModule();
            if (lastVisited) {
                navigateToModule(lastVisited);
            } else {
                // Navigate to first incomplete module
                const firstIncomplete = getFirstIncompleteModule();
                if (firstIncomplete) {
                    navigateToModule(firstIncomplete);
                }
            }
        });
    }
    
    // Random module button
    const randomBtn = document.getElementById('randomBtn');
    if (randomBtn) {
        randomBtn.addEventListener('click', function() {
            const incompleteModules = getIncompleteModules();
            if (incompleteModules.length > 0) {
                const randomModule = incompleteModules[Math.floor(Math.random() * incompleteModules.length)];
                navigateToModule(randomModule);
            }
        });
    }
}

function updateStats() {
    try {
        const completedModules = getCompletedModules();
        const totalScore = calculateTotalScore();
        const streakDays = getStreakDays();
        const achievements = getAchievements();
        
        // Update stat cards
        const completedElement = document.getElementById('completedModules');
        const totalScoreElement = document.getElementById('totalScore');
        const streakDaysElement = document.getElementById('streakDays');
        const achievementsElement = document.getElementById('achievements');
        
        if (completedElement) completedElement.textContent = completedModules;
        if (totalScoreElement) totalScoreElement.textContent = totalScore;
        if (streakDaysElement) streakDaysElement.textContent = streakDays;
        if (achievementsElement) achievementsElement.textContent = achievements;
        
        // Update progress circle
        const totalModules = getTotalModules();
        const progress = totalModules === 0 ? 0 : Math.round((completedModules / totalModules) * 100);
        updateProgressCircle(progress);
    } catch (error) {
        console.error('Error updating stats:', error);
    }
}

function getStreakDays() {
    try {
        return parseInt(localStorage.getItem('streakDays') || '0');
    } catch (error) {
        console.error('Error getting streak days:', error);
        return 0;
    }
}

function getAchievements() {
    try {
        const completedModules = getCompletedModules();
        const totalModules = getTotalModules();
        let achievements = 0;
        
        if (completedModules >= 1) achievements++;
        if (completedModules >= Math.ceil(totalModules / 3)) achievements++;
        if (completedModules >= Math.ceil(totalModules * 2 / 3)) achievements++;
        if (completedModules >= totalModules) achievements++;
        
        return achievements;
    } catch (error) {
        console.error('Error getting achievements:', error);
        return 0;
    }
}

function getLastVisitedModule() {
    try {
        const visitedModules = JSON.parse(localStorage.getItem('visitedModules') || '[]');
        return visitedModules[visitedModules.length - 1];
    } catch (error) {
        console.error('Error getting last visited module:', error);
        return null;
    }
}

function getFirstIncompleteModule() {
    try {
        const completedModules = JSON.parse(localStorage.getItem('completedModules') || '[]');
        const completedNames = completedModules.map(m => m.name);
        const allModules = [
            'sqli', 'xss', 'csrf', 'auth', 'upload', 'session'
        ];
        return allModules.find(module => !completedNames.includes(module));
    } catch (error) {
        console.error('Error getting first incomplete module:', error);
        return null;
    }
}

function getIncompleteModules() {
    try {
        const completedModules = JSON.parse(localStorage.getItem('completedModules') || '[]');
        const completedNames = completedModules.map(m => m.name);
        const allModules = [
            'sqli', 'xss', 'csrf', 'auth', 'upload', 'session'
        ];
        return allModules.filter(module => !completedNames.includes(module));
    } catch (error) {
        console.error('Error getting incomplete modules:', error);
        return [];
    }
}

// Activity feed functionality
function initializeActivityFeed() {
    updateActivityFeed();
}

function updateActivityFeed() {
    try {
        const activityList = document.getElementById('activityList');
        if (!activityList) return;
        
        const activities = getRecentActivities();
        
        activityList.innerHTML = activities.map(activity => `
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="${activity.icon}"></i>
                </div>
                <div class="activity-content">
                    <h4>${activity.title}</h4>
                    <p>${activity.description}</p>
                    <span class="activity-time">${activity.time}</span>
                </div>
            </div>
        `).join('');
    } catch (error) {
        console.error('Error updating activity feed:', error);
    }
}

function getRecentActivities() {
    try {
        const completedModules = JSON.parse(localStorage.getItem('completedModules') || '[]');
        const activities = [];
        
        // Add completed modules to activities
        completedModules.slice(-3).forEach(module => {
            activities.push({
                icon: 'fas fa-check-circle',
                title: `${module.name.replace('_', ' ').toUpperCase()} Module`,
                description: `Completed with ${module.score || 100}% score`,
                time: formatTimeAgo(new Date(module.completedAt))
            });
        });
        
        // Add default activities if none exist
        if (activities.length === 0) {
            activities.push(
                {
                    icon: 'fas fa-play-circle',
                    title: 'Welcome to CCST Labs',
                    description: 'Start your security learning journey',
                    time: 'Just now'
                },
                {
                    icon: 'fas fa-trophy',
                    title: 'First Achievement Unlocked',
                    description: 'Complete your first module to earn achievements',
                    time: 'Coming soon'
                }
            );
        }
        
        return activities.slice(0, 3);
    } catch (error) {
        console.error('Error getting recent activities:', error);
        return [];
    }
}

function formatTimeAgo(date) {
    try {
        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);
        
        if (diffInSeconds < 60) return 'Just now';
        if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} minutes ago`;
        if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} hours ago`;
        return `${Math.floor(diffInSeconds / 86400)} days ago`;
    } catch (error) {
        console.error('Error formatting time ago:', error);
        return 'Recently';
    }
}

// Notification system
function initializeNotifications() {
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationPanel = document.getElementById('notificationPanel');
    const closeNotifications = document.getElementById('closeNotifications');
    
    if (notificationBtn && notificationPanel) {
        notificationBtn.addEventListener('click', function() {
            notificationPanel.classList.toggle('show');
        });
    }
    
    if (closeNotifications && notificationPanel) {
        closeNotifications.addEventListener('click', function() {
            notificationPanel.classList.remove('show');
        });
    }
}

function showNotification(message, type = 'info') {
    try {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        
        // Add icon based on type
        const icon = document.createElement('i');
        icon.className = getNotificationIcon(type);
        notification.insertBefore(icon, notification.firstChild);
        
        document.body.appendChild(notification);
        
        // Show notification
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);
        
        // Hide and remove notification
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }, 4000);
    } catch (error) {
        console.error('Error showing notification:', error);
    }
}

function getNotificationIcon(type) {
    const icons = {
        'info': 'fas fa-info-circle',
        'success': 'fas fa-check-circle',
        'warning': 'fas fa-exclamation-triangle',
        'error': 'fas fa-times-circle'
    };
    return icons[type] || icons.info;
}

// Keyboard navigation
function initializeKeyboardNavigation() {
    document.addEventListener('keydown', function(e) {
        // Escape key to close any open modals or overlays
        if (e.key === 'Escape') {
            const sidebar = document.getElementById('sidebar');
            const notificationPanel = document.getElementById('notificationPanel');
            const overlay = document.getElementById('overlay');
            
            if (sidebar && sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                if (overlay) overlay.classList.remove('show');
            }
            
            if (notificationPanel && notificationPanel.classList.contains('show')) {
                notificationPanel.classList.remove('show');
            }
        }
        
        // Ctrl/Cmd + K to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            const searchInput = document.getElementById('searchInput');
            if (searchInput) searchInput.focus();
        }
        
        // Ctrl/Cmd + D to toggle dark mode
        if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
            e.preventDefault();
            const darkModeToggle = document.getElementById('darkModeToggle');
            if (darkModeToggle) darkModeToggle.click();
        }
    });
}

// Accessibility features
function initializeAccessibility() {
    try {
        // Add ARIA labels and roles
        const moduleCards = document.querySelectorAll('.module-card');
        
        moduleCards.forEach((card, index) => {
            card.setAttribute('role', 'button');
            const title = card.querySelector('h3')?.textContent || `Module ${index + 1}`;
            card.setAttribute('aria-label', `Module ${index + 1}: ${title}`);
            card.setAttribute('aria-describedby', `module-${index + 1}-desc`);
        });
        
        // Add skip to content link
        const skipLink = document.createElement('a');
        skipLink.href = '#main-content';
        skipLink.textContent = 'Skip to main content';
        skipLink.className = 'skip-link';
        skipLink.style.cssText = `
            position: absolute;
            top: -40px;
            left: 6px;
            background: var(--primary-blue);
            color: white;
            padding: 8px;
            text-decoration: none;
            border-radius: 4px;
            z-index: 10000;
        `;
        
        skipLink.addEventListener('focus', function() {
            this.style.top = '6px';
        });
        
        skipLink.addEventListener('blur', function() {
            this.style.top = '-40px';
        });
        
        document.body.insertBefore(skipLink, document.body.firstChild);
    } catch (error) {
        console.error('Error initializing accessibility:', error);
    }
}

// Utility functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func.apply(this, args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function updateActiveNav(activeLink) {
    const navLinks = document.querySelectorAll('.sidebar-nav a');
    navLinks.forEach(link => {
        link.parentElement.classList.remove('active');
    });
    activeLink.parentElement.classList.add('active');
}

// Module completion tracking
function markModuleAsCompleted(moduleName, score = 100) {
    try {
        const completedModules = JSON.parse(localStorage.getItem('completedModules') || '[]');
        
        // Check if module is already completed
        const existingIndex = completedModules.findIndex(module => module.name === moduleName);
        
        if (existingIndex === -1) {
            // Add new completion
            completedModules.push({
                name: moduleName,
                completedAt: new Date().toISOString(),
                score: score
            });
        } else {
            // Update existing completion if score is higher
            if (score > completedModules[existingIndex].score) {
                completedModules[existingIndex].score = score;
                completedModules[existingIndex].completedAt = new Date().toISOString();
            }
        }
        
        localStorage.setItem('completedModules', JSON.stringify(completedModules));
        
        // Update progress and stats
        if (window.updateProgress) {
            window.updateProgress();
        }
        updateStats();
        updateActivityFeed();
        
        showNotification(`Module "${moduleName}" completed! Score: ${score}`, 'success');
    } catch (error) {
        console.error('Error marking module as completed:', error);
        showNotification('Failed to save completion status.', 'error');
    }
}

// Export functions for use in module pages
window.CCSTLabs = {
    showNotification,
    debounce,
    markModuleAsCompleted,
    updateProgress: function() {
        if (window.updateProgress) {
            window.updateProgress();
        }
    },
    updateStats,
    updateActivityFeed
};

// Add global error handling
window.addEventListener('error', function(e) {
    console.error('Application error:', e.error);
    showNotification('An error occurred. Please refresh the page.', 'error');
});

// Add performance monitoring
window.addEventListener('load', function() {
    // Log performance metrics
    if ('performance' in window) {
        const perfData = performance.getEntriesByType('navigation')[0];
        console.log('Page load time:', perfData.loadEventEnd - perfData.loadEventStart, 'ms');
    }
    
    // Add loaded class to body
    document.body.classList.add('loaded');
});

// Add service worker for offline functionality (if supported)
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js')
            .then(function(registration) {
                console.log('ServiceWorker registration successful');
            })
            .catch(function(err) {
                console.log('ServiceWorker registration failed');
            });
    });
}

// Helper function to get total modules
function getTotalModules() {
    return 6; // SQL Injection, XSS, CSRF, Auth, Upload, Session
} 