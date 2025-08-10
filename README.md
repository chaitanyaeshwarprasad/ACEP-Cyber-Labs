# 🛡️ ACEP CYBER LABS

**Created by A Chaitanya Eshwar Prasad**

A modern ethical hacking lab platform featuring realistic web vulnerabilities (SQLi, XSS, CSRF, etc.) for training and education.

## 🎯 Features

- **6 Vulnerability-Focused Labs**: Comprehensive coverage of web security threats
- **Neon Blue Modern UI**: Clean, professional interface on dark background
- **Responsive Design**: Optimized for all screen sizes and devices
- **Pre-configured for Kali Linux**: Ready-to-use security testing environment
- **Interactive Learning**: Hands-on vulnerability demonstrations
- **Progress Tracking**: Real-time lab completion monitoring

## 🧪 Security Labs Included

| Lab | Vulnerability Type | Description |
|-----|-------------------|-------------|
| **SQL Injection** | Database Attacks | SQL query manipulation and database exploitation |
| **Cross-Site Scripting (XSS)** | Client-Side Attacks | Script injection and execution attacks |
| **CSRF** | Request Forgery | Cross-Site Request Forgery attacks |
| **Broken Authentication** | Auth Bypass | Authentication and authorization bypass techniques |
| **File Upload Vulnerabilities** | File Attacks | Malicious file upload and execution |
| **Session Management** | Session Attacks | Session hijacking and fixation techniques |

## 🚀 Quick Setup

### Prerequisites
- Kali Linux (recommended) or any Linux distribution
- Apache/Nginx web server
- PHP 7.4+ with required extensions
- Modern web browser

### Installation Steps

1. **Clone the repository:**
   ```bash
   git clone https://github.com/chaitanyaeshwarprasad/ACEP-Cyber-Labs.git
   cd ACEP-Cyber-Labs
   ```

2. **Copy to web root:**
   ```bash
   sudo cp index.php /var/www/html/
   sudo cp config.php /var/www/html/
   sudo cp -r assets /var/www/html/
   sudo cp -r labs /var/www/html/
   sudo cp README.md /var/www/html/
   sudo cp setup.sh /var/www/html/
   ```

3. **Set proper permissions:**
   ```bash
   sudo chown -R www-data:www-data /var/www/html/
   sudo chmod -R 777 /var/www/html/
   ```

4. **Run the automated setup script:**
   ```bash
   cd /var/www/html/
   chmod +x setup.sh
   ./setup.sh
   ```

5. **Access the application:**
   ```
   http://127.0.0.1/
   ```

## 🛠️ Technology Stack

- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Backend**: PHP with SQLite database
- **Web Server**: Apache with mod_rewrite
- **Environment**: Kali Linux (recommended)
- **UI Framework**: Custom CSS with Font Awesome icons
- **Responsive Design**: Mobile-first approach

## 📱 Demo & Screenshots

The platform features a modern, responsive interface with:
- Dark theme with neon blue accents
- Interactive dashboard with progress tracking
- Category-based lab organization
- Real-time statistics and achievements
- Professional footer with creator information

## ⚠️ Important Security Notice

**FOR EDUCATIONAL USE ONLY**

- This platform contains **intentional security vulnerabilities**
- **Never deploy in a production environment**
- Use only in isolated development/learning environments
- Designed for ethical hacking education and training
- Respect responsible disclosure practices

## 🔧 Advanced Configuration

### Manual Setup (Alternative)
If you prefer manual configuration:

```bash
# Install LAMP stack manually
sudo apt update
sudo apt install apache2 php mariadb-server php-mysql

# Configure Apache virtual host
sudo a2enmod rewrite
sudo systemctl restart apache2

# Set database permissions
sudo mysql_secure_installation
```

### Customization
- Modify `config.php` for database settings
- Edit `assets/style.css` for theme customization
- Update lab content in individual `labs/*/index.php` files

## 📁 Project Structure

```
ACEP-Cyber-Labs/
├── assets/              # CSS, JavaScript, and assets
│   ├── style.css       # Main stylesheet
│   └── script.js       # Dashboard functionality
├── labs/               # Individual vulnerability labs
│   ├── sqli/          # SQL Injection lab
│   ├── xss/           # Cross-Site Scripting lab
│   ├── csrf/          # CSRF lab
│   ├── auth/          # Authentication lab
│   ├── upload/        # File Upload lab
│   └── session/       # Session Management lab
├── config.php          # Application configuration
├── index.php           # Main dashboard
├── setup.sh            # Automated installation script
└── README.md           # This file
```

## 🎓 Learning Path

1. **Start with Authentication Lab** - Learn basic security concepts
2. **Progress to SQL Injection** - Understand database vulnerabilities
3. **Explore XSS and CSRF** - Master client-side attacks
4. **Practice File Upload** - Learn file-based vulnerabilities
5. **Master Session Management** - Understand session security
6. **Complete all labs** - Earn your cybersecurity certification

## 📞 Connect with Creator

**A Chaitanya Eshwar Prasad**

- 🌐 **Website**: [chaitanyaeshwarprasad.com](https://chaitanyaeshwarprasad.com)
- 📸 **Instagram**: [instagram.com/acep.tech.in.telugu](https://instagram.com/acep.tech.in.telugu)
- 💼 **LinkedIn**: [linkedin.com/in/chaitanya-eshwar-prasad](https://linkedin.com/in/chaitanya-eshwar-prasad)
- 🛡️ **YesWeHack**: [yeswehack.com/hunters/chaitanya-eshwar-prasad](https://yeswehack.com/hunters/chaitanya-eshwar-prasad)
- 🎥 **YouTube**: [youtube.com/@chaitanya.eshwar.prasad](https://youtube.com/@chaitanya.eshwar.prasad)
- 🐙 **GitHub**: [github.com/chaitanyaeshwarprasad](https://github.com/chaitanyaeshwarprasad)

## 🤝 Contributing

This project is designed for educational purposes. If you find any issues or have suggestions:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## 📄 License

This project is created for educational purposes. Please respect ethical hacking principles and use responsibly.

---

## 🚀 Ready to Start?

```bash
# Quick start
git clone https://github.com/chaitanyaeshwarprasad/ACEP-Cyber-Labs.git
cd ACEP-Cyber-Labs
sudo cp index.php /var/www/html/
sudo cp config.php /var/www/html/
sudo cp -r assets /var/www/html/
sudo cp -r labs /var/www/html/
sudo cp README.md /var/www/html/
sudo cp setup.sh /var/www/html/
sudo chmod -R 777 /var/www/html/
cd /var/www/html/
./setup.sh
```

**Access your lab at:** `http://127.0.0.1/`

---

*Happy Learning! 🎓*

**⭐ Star this repository if you find it helpful!**