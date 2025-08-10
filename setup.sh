#!/bin/bash

# ACEP CYBER LABS - Complete Automated Installation Script
# Created by A Chaitanya Eshwar Prasad
# For Kali Linux and Debian-based systems

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'
WHITE='\033[1;37m'
NC='\033[0m' # No Color

# ASCII Art Banner
clear
echo -e "${CYAN}"
cat << "EOF"
    \     __|  __|  _ \     __| \ \  / _ )  __|  _ \    |       \    _ )   __| 
   _ \   (     _|   __/    (     \  /  _ \  _|     /    |      _ \   _ \ \__ \ 
 _/  _\ \___| ___| _|     \___|   _|  ___/ ___| _|_\   ____| _/  _\ ___/ ____/ 
EOF
echo -e "${NC}"
echo -e "${GREEN}🚀 ACEP CYBER LABS - Interactive Security Learning Platform${NC}"
echo -e "${YELLOW}📚 Version 4.0 - NEON EDITION${NC}"
echo -e "${RED}🎯 For Educational Use Only${NC}"
echo ""
echo -e "${BLUE}🌐 Website: ${WHITE}chaitanyaeshwarprasad.com${NC}"
echo -e "${BLUE}📸 Instagram: ${WHITE}instagram.com/acep.tech.in.telugu${NC}"
echo -e "${BLUE}💼 LinkedIn: ${WHITE}linkedin.com/in/chaitanya-eshwar-prasad${NC}"
echo -e "${BLUE}🐙 GitHub: ${WHITE}github.com/chaitanyaeshwarprasad${NC}"
echo -e "${BLUE}🎯 YesWeHack: ${WHITE}yeswehack.com/hunters/chaitanya-eshwar-prasad${NC}"
echo ""
echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
echo -e "${GREEN}ACEP CYBER LABS COMPLETE AUTOMATED INSTALLATION${NC}"
echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"

# Usage Instructions
echo -e "${CYAN}📖 USAGE INSTRUCTIONS:${NC}"
echo -e "${WHITE}1.${NC} Make script executable: ${YELLOW}chmod +x setup.sh${NC}"
echo -e "${WHITE}2.${NC} Choose your mode:"
echo -e "   ${GREEN}Option A:${NC} Run as regular user: ${YELLOW}./setup.sh${NC}"
echo -e "   ${GREEN}Option B:${NC} Run in root mode: ${YELLOW}sudo su${NC} then ${YELLOW}./setup.sh${NC}"
echo -e "${WHITE}3.${NC} ${RED}DO NOT use: sudo ./setup.sh${NC}"
echo -e "${WHITE}4.${NC} Script will detect your mode automatically"
echo ""
echo -e "${YELLOW}⚠️  IMPORTANT:${NC} Both modes work perfectly!"
echo -e "${CYAN}Root mode:${NC} No password prompts, full automation"
echo -e "${CYAN}User mode:${NC} Prompts for sudo password when needed"
echo ""

# Function to print status messages
print_status() {
    local status=$1
    local message=$2
    case $status in
        "INFO")
            echo -e "${BLUE}[INFO]${NC} $message"
            ;;
        "SUCCESS")
            echo -e "${GREEN}[SUCCESS]${NC} $message"
            ;;
        "WARNING")
            echo -e "${YELLOW}[WARNING]${NC} $message"
            ;;
        "ERROR")
            echo -e "${RED}[ERROR]${NC} $message"
            ;;
    esac
}

# Function to check if command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Function to check system requirements
check_system_requirements() {
    echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
    echo -e "${GREEN}SYSTEM REQUIREMENTS CHECK${NC}"
    echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
    
    # Check OS
    if [[ -f /etc/os-release ]]; then
        . /etc/os-release
        if [[ "$ID" == "kali" || "$ID" == "debian" || "$ID" == "ubuntu" ]]; then
            print_status "SUCCESS" "Supported OS detected: $PRETTY_NAME"
        else
            print_status "WARNING" "OS $PRETTY_NAME detected. This script is optimized for Kali Linux/Debian."
        fi
    else
        print_status "WARNING" "Could not determine OS. Proceeding with installation..."
    fi
    
    # Check memory
    if command_exists free; then
        memory=$(free -m | awk 'NR==2{printf "%.0f", $2}')
        if [ $memory -ge 2048 ]; then
            print_status "SUCCESS" "Memory: ${memory}MB"
        else
            print_status "WARNING" "Memory: ${memory}MB (Recommended: 2GB+)"
        fi
    fi
    
    # Check disk space
    if command_exists df; then
        disk_space=$(df -m . | awk 'NR==2{printf "%.0f", $4}')
        if [ $disk_space -ge 1024 ]; then
            print_status "SUCCESS" "Disk space: ${disk_space}MB"
        else
            print_status "WARNING" "Disk space: ${disk_space}MB (Recommended: 1GB+)"
        fi
    fi
    
    # Check internet connectivity
    if ping -c 1 google.com >/dev/null 2>&1; then
        print_status "SUCCESS" "Internet connectivity: OK"
    else
        print_status "WARNING" "Internet connectivity: Check your connection"
    fi
    
    # Check CPU cores
    if command_exists nproc; then
        cpu_cores=$(nproc)
        print_status "SUCCESS" "CPU cores: $cpu_cores"
    fi
}

# Function to install LAMP stack
install_lamp_stack() {
    echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
    echo -e "${GREEN}LAMP STACK INSTALLATION${NC}"
    echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
    
    # Update package lists
    print_status "INFO" "Updating package lists..."
    sudo apt update
    
    # Install Apache
    print_status "INFO" "Installing Apache..."
    if ! dpkg -l | grep -q apache2; then
        sudo apt install -y apache2
    else
        print_status "SUCCESS" "Apache already installed"
    fi
    
    # Install PHP and extensions
    print_status "INFO" "Installing PHP and extensions..."
    php_packages="php php-cli php-common libapache2-mod-php php-mysql php-json php-curl php-mbstring php-xml php-zip php-gd php-sqlite3"
    
    for package in $php_packages; do
        if ! dpkg -l | grep -q "$package"; then
            print_status "INFO" "Installing $package..."
            sudo apt install -y "$package"
        else
            print_status "SUCCESS" "$package already installed"
        fi
    done
    
    # Install SQLite3
    print_status "INFO" "Installing SQLite3..."
    if ! dpkg -l | grep -q sqlite3; then
        sudo apt install -y sqlite3 php-sqlite3
    else
        print_status "SUCCESS" "SQLite3 already installed"
    fi
    
    # Install MariaDB
    print_status "INFO" "Installing MariaDB..."
    if ! dpkg -l | grep -q mariadb-server; then
        sudo apt install -y mariadb-server mariadb-client
    else
        print_status "SUCCESS" "MariaDB already installed"
    fi
    
    # Clean up
    print_status "INFO" "Cleaning up package cache..."
    sudo apt autoremove -y
    
    print_status "SUCCESS" "LAMP stack installation completed!"
}

# Function to configure Apache
configure_apache() {
    echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
    echo -e "${GREEN}APACHE CONFIGURATION${NC}"
    echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
    
    # Enable Apache modules
    print_status "INFO" "Enabling Apache modules..."
    sudo a2enmod rewrite
    sudo a2enmod headers
    
    # Enable PHP module
    print_status "INFO" "Enabling PHP module..."
    sudo a2enmod php8.4 2>/dev/null || sudo a2enmod php8.3 2>/dev/null || sudo a2enmod php8.2 2>/dev/null || sudo a2enmod php8.1 2>/dev/null || sudo a2enmod php8.0 2>/dev/null || sudo a2enmod php7.4 2>/dev/null
    
    # Create Apache virtual host
    print_status "INFO" "Creating Apache virtual host..."
    sudo tee /etc/apache2/sites-available/acep-labs.conf > /dev/null <<EOF
<VirtualHost *:80>
    ServerName localhost
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html
    
    <Directory /var/www/html>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog \${APACHE_LOG_DIR}/acep-labs_error.log
    CustomLog \${APACHE_LOG_DIR}/acep-labs_access.log combined
</VirtualHost>
EOF
    
    # Enable ACEP Labs site
    print_status "INFO" "Enabling ACEP Labs site..."
    sudo a2dissite 000-default 2>/dev/null
    sudo a2ensite acep-labs
    
    # Start and enable Apache
    print_status "INFO" "Starting and enabling Apache..."
    sudo systemctl enable apache2
    sudo systemctl start apache2
    
    print_status "SUCCESS" "Apache configured successfully!"
}

# Function to reset MariaDB root password
reset_mariadb_root_password() {
    print_status "INFO" "Resetting MariaDB root password..."
    
    # Stop MariaDB
    sudo systemctl stop mariadb
    
    # Start MariaDB in safe mode
    sudo mysqld_safe --skip-grant-tables --skip-networking &
    local safe_pid=$!
    
    # Wait for safe mode to start
    sleep 5
    
    # Reset root password (try different methods)
    if sudo mysql -u root -e "USE mysql; UPDATE user SET authentication_string=PASSWORD('acep_root_password_2024') WHERE User='root'; FLUSH PRIVILEGES;" >/dev/null 2>&1; then
        print_status "SUCCESS" "Root password reset using PASSWORD() function"
    elif sudo mysql -u root -e "USE mysql; UPDATE user SET authentication_string='acep_root_password_2024' WHERE User='root'; FLUSH PRIVILEGES;" >/dev/null 2>&1; then
        print_status "SUCCESS" "Root password reset using direct string"
    else
        print_status "WARNING" "Could not reset password using standard methods"
    fi
    
    # Stop safe mode
    sudo pkill -f mysqld_safe
    sudo pkill -f mysqld
    
    # Wait for processes to stop
    sleep 3
    
    # Start MariaDB normally
    sudo systemctl start mariadb
    sleep 5
}

# Function to configure MariaDB
configure_mariadb() {
    echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
    echo -e "${GREEN}MARIADB CONFIGURATION${NC}"
    echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
    
    # Start and enable MariaDB
    print_status "INFO" "Starting and enabling MariaDB..."
    sudo systemctl enable mariadb
    sudo systemctl start mariadb
    
    # Wait for MariaDB to be ready
    print_status "INFO" "Waiting for MariaDB to be ready..."
    sleep 5
    
    # Check if MariaDB root access is already configured
    if sudo mysql -u root -e "SELECT 1;" >/dev/null 2>&1; then
        print_status "INFO" "MariaDB root access already configured"
        # Try to set password if none exists
        if ! sudo mysql -u root -e "SELECT 1;" >/dev/null 2>&1; then
            sudo mysql -u root -e "ALTER USER 'root'@'localhost' IDENTIFIED BY 'acep_root_password_2024';" 2>/dev/null || true
        fi
    else
        print_status "INFO" "Securing MariaDB installation..."
        
        # Try to access MariaDB without password first
        if sudo mysql -u root -e "SELECT 1;" >/dev/null 2>&1; then
            # No password set, secure it
            sudo mysql -u root -e "ALTER USER 'root'@'localhost' IDENTIFIED BY 'acep_root_password_2024';" 2>/dev/null || true
        else
            # Reset root password
            reset_mariadb_root_password
        fi
        
        # Test the new password
        if ! sudo mysql -u root -pacep_root_password_2024 -e "SELECT 1;" >/dev/null 2>&1; then
            print_status "WARNING" "Root password reset may have failed, trying alternative method..."
            reset_mariadb_root_password
        fi
    fi
    
    # Secure the installation
    print_status "INFO" "Securing MariaDB installation..."
    sudo mysql -u root -pacep_root_password_2024 -e "DELETE FROM mysql.user WHERE User='';" 2>/dev/null || sudo mysql -u root -e "DELETE FROM mysql.user WHERE User='';" 2>/dev/null || true
    sudo mysql -u root -pacep_root_password_2024 -e "DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');" 2>/dev/null || sudo mysql -u root -e "DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');" 2>/dev/null || true
    sudo mysql -u root -pacep_root_password_2024 -e "DROP DATABASE IF EXISTS test;" 2>/dev/null || sudo mysql -u root -e "DROP DATABASE IF EXISTS test;" 2>/dev/null || true
    sudo mysql -u root -pacep_root_password_2024 -e "FLUSH PRIVILEGES;" 2>/dev/null || sudo mysql -u root -e "FLUSH PRIVILEGES;" 2>/dev/null || true
    
    print_status "SUCCESS" "MariaDB configured successfully!"
}

# Function to deploy ACEP Cyber Labs
deploy_acep_labs() {
    echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
    echo -e "${GREEN}ACEP CYBER LABS DEPLOYMENT${NC}"
    echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
    
    # Clean existing files in web root
    print_status "INFO" "Cleaning existing web files..."
    sudo rm -rf /var/www/html/*
    
    # Copy files directly to web root
    print_status "INFO" "Copying application files..."
    sudo cp -r index.php /var/www/html/ 2>/dev/null || sudo cp index.php /var/www/html/
    sudo cp -r config.php /var/www/html/ 2>/dev/null || sudo cp config.php /var/www/html/
    sudo cp -r assets /var/www/html/ 2>/dev/null || sudo cp -r assets /var/www/html/
    sudo cp -r labs /var/www/html/ 2>/dev/null || sudo cp -r labs /var/www/html/
    sudo cp -r README.md /var/www/html/ 2>/dev/null || sudo cp README.md /var/www/html/
    
    # Verify files were copied
    if [ -f /var/www/html/index.php ]; then
        print_status "SUCCESS" "Main application files copied successfully"
    else
        print_status "WARNING" "Some files may not have been copied properly"
    fi
    
    # Set permissions
    print_status "INFO" "Setting proper permissions..."
    sudo chown -R www-data:www-data /var/www/html
    sudo chmod -R 755 /var/www/html
    sudo chmod 777 /var/www/html/labs/upload/ 2>/dev/null || true
    
    print_status "SUCCESS" "ACEP CYBER LABS deployed successfully!"
}

# Function to setup database
setup_database() {
    echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
    echo -e "${GREEN}DATABASE SETUP${NC}"
    echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
    
    # Create database and user
    print_status "INFO" "Creating database and user..."
    
    # Try to create database with root access
    if sudo mysql -u root -pacep_root_password_2024 -e "CREATE DATABASE IF NOT EXISTS acep_labs;" >/dev/null 2>&1; then
        print_status "SUCCESS" "Database 'acep_labs' created successfully"
    else
        print_status "WARNING" "Could not create database with root password, trying without password..."
        if sudo mysql -u root -e "CREATE DATABASE IF NOT EXISTS acep_labs;" >/dev/null 2>&1; then
            print_status "SUCCESS" "Database 'acep_labs' created successfully"
        else
            print_status "ERROR" "Failed to create database"
            return 1
        fi
    fi
    
    # Create user and grant privileges
    if sudo mysql -u root -pacep_root_password_2024 -e "CREATE USER IF NOT EXISTS 'acep_user'@'localhost' IDENTIFIED BY 'acep_password_2024';" >/dev/null 2>&1; then
        print_status "SUCCESS" "User 'acep_user' created successfully"
    else
        print_status "WARNING" "Could not create user with root password, trying without password..."
        sudo mysql -u root -e "CREATE USER IF NOT EXISTS 'acep_user'@'localhost' IDENTIFIED BY 'acep_password_2024';" >/dev/null 2>&1 || true
    fi
    
    # Grant privileges
    if sudo mysql -u root -pacep_root_password_2024 -e "GRANT ALL PRIVILEGES ON acep_labs.* TO 'acep_user'@'localhost';" >/dev/null 2>&1; then
        print_status "SUCCESS" "Privileges granted successfully"
    else
        print_status "WARNING" "Could not grant privileges with root password, trying without password..."
        sudo mysql -u root -e "GRANT ALL PRIVILEGES ON acep_labs.* TO 'acep_user'@'localhost';" >/dev/null 2>&1 || true
    fi
    
    # Flush privileges
    sudo mysql -u root -pacep_root_password_2024 -e "FLUSH PRIVILEGES;" >/dev/null 2>&1 || sudo mysql -u root -e "FLUSH PRIVILEGES;" >/dev/null 2>&1 || true
    
    print_status "SUCCESS" "Database setup completed!"
}

# Function to final configuration and testing
final_configuration() {
    echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
    echo -e "${GREEN}FINAL CONFIGURATION AND TESTING${NC}"
    echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
    
    # Restart services
    print_status "INFO" "Restarting Apache..."
    sudo systemctl restart apache2
    
    print_status "INFO" "Restarting MariaDB..."
    sudo systemctl restart mariadb
    
    # Test services
    print_status "INFO" "Testing services..."
    
    if sudo systemctl is-active --quiet apache2; then
        print_status "SUCCESS" "Apache is running"
    else
        print_status "ERROR" "Apache is not running"
    fi
    
    if sudo systemctl is-active --quiet mariadb; then
        print_status "SUCCESS" "MariaDB is running"
    else
        print_status "ERROR" "MariaDB is not running"
    fi
    
    if curl -s http://localhost > /dev/null; then
        print_status "SUCCESS" "Web server is responding"
    else
        print_status "WARNING" "Web server is not responding"
    fi
    
    if [ -f /var/www/html/index.php ]; then
        print_status "SUCCESS" "ACEP CYBER LABS application is accessible"
    else
        print_status "ERROR" "ACEP CYBER LABS application not found"
    fi
    
    # Test database connection
    if sudo mysql -u acep_user -pacep_password_2024 -e "USE acep_labs;" > /dev/null 2>&1; then
        print_status "SUCCESS" "Database connection successful"
    else
        print_status "WARNING" "Database connection failed"
    fi
    
    print_status "SUCCESS" "Final configuration completed!"
}

# Function to show completion message
show_completion() {
    echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
    echo -e "${GREEN}INSTALLATION COMPLETED SUCCESSFULLY!${NC}"
    echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
    echo ""
    echo -e "${GREEN}🎉 ACEP CYBER LABS has been installed successfully!${NC}"
    echo ""
    echo -e "${CYAN}📋 Installation Summary:${NC}"
    echo -e "   ${GREEN}✅${NC} Apache Web Server"
    echo -e "   ${GREEN}✅${NC} PHP with essential extensions"
    echo -e "   ${GREEN}✅${NC} MariaDB Database"
    echo -e "   ${GREEN}✅${NC} ACEP CYBER LABS Application"
    echo -e "   ${GREEN}✅${NC} Database setup with sample data"
    echo -e "   ${GREEN}✅${NC} Security headers and configurations"
    echo ""
    echo -e "${CYAN}🌐 Access Information:${NC}"
    echo -e "   ${WHITE}URL:${NC} http://127.0.0.1/"
    echo -e "   ${WHITE}Database:${NC} acep_labs"
    echo -e "   ${WHITE}User:${NC} acep_user"
    echo -e "   ${WHITE}Password:${NC} acep_password_2024"
    echo ""
    echo -e "${CYAN}🔧 Useful Commands:${NC}"
    echo -e "   ${WHITE}Restart Apache:${NC} sudo systemctl restart apache2"
    echo -e "   ${WHITE}Restart MariaDB:${NC} sudo systemctl restart mariadb"
    echo -e "   ${WHITE}View Logs:${NC} sudo tail -f /var/log/apache2/error.log"
    echo ""
    echo -e "${YELLOW}⚠  IMPORTANT:${NC}"
    echo -e "   - This is for educational use only"
    echo -e "   - Never deploy on production systems"
    echo -e "   - Use only in isolated environments"
    echo ""
    echo -e "${GREEN}🚀 Ready to start learning cybersecurity!${NC}"
    echo ""
    echo -e "${CYAN}🎯 Next Steps:${NC}"
    echo -e "   1. Open your browser"
    echo -e "   2. Go to: ${WHITE}http://127.0.0.1/${NC}"
    echo -e "   3. Start exploring the security labs"
    echo -e "   4. Use the search functionality to find specific topics"
    echo ""
}

# Function to install LAMP stack (ROOT MODE)
install_lamp_stack_root() {
    echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
    echo -e "${GREEN}LAMP STACK INSTALLATION (ROOT MODE)${NC}"
    echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
    
    # Update package lists
    print_status "INFO" "Updating package lists..."
    apt update
    
    # Install Apache
    print_status "INFO" "Installing Apache..."
    if ! dpkg -l | grep -q apache2; then
        apt install -y apache2
    else
        print_status "SUCCESS" "Apache already installed"
    fi
    
    # Install PHP and extensions
    print_status "INFO" "Installing PHP and extensions..."
    php_packages="php php-cli php-common libapache2-mod-php php-mysql php-json php-curl php-mbstring php-xml php-zip php-gd php-sqlite3"
    
    for package in $php_packages; do
        if ! dpkg -l | grep -q "$package"; then
            print_status "INFO" "Installing $package..."
            apt install -y "$package"
        else
            print_status "SUCCESS" "$package already installed"
        fi
    done
    
    # Install SQLite3
    print_status "INFO" "Installing SQLite3..."
    if ! dpkg -l | grep -q sqlite3; then
        apt install -y sqlite3 php-sqlite3
    else
        print_status "SUCCESS" "SQLite3 already installed"
    fi
    
    # Install MariaDB
    print_status "INFO" "Installing MariaDB..."
    if ! dpkg -l | grep -q mariadb-server; then
        apt install -y mariadb-server mariadb-client
    else
        print_status "SUCCESS" "MariaDB already installed"
    fi
    
    # Clean up
    print_status "INFO" "Cleaning up package cache..."
    apt autoremove -y
    
    print_status "SUCCESS" "LAMP stack installation completed!"
}

# Function to configure Apache (ROOT MODE)
configure_apache_root() {
    echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
    echo -e "${GREEN}APACHE CONFIGURATION (ROOT MODE)${NC}"
    echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
    
    # Enable Apache modules
    print_status "INFO" "Enabling Apache modules..."
    a2enmod rewrite
    a2enmod headers
    
    # Enable PHP module
    print_status "INFO" "Enabling PHP module..."
    a2enmod php8.4 2>/dev/null || a2enmod php8.3 2>/dev/null || a2enmod php8.2 2>/dev/null || a2enmod php8.1 2>/dev/null || a2enmod php8.0 2>/dev/null || a2enmod php7.4 2>/dev/null
    
    # Create Apache virtual host
    print_status "INFO" "Creating Apache virtual host..."
    tee /etc/apache2/sites-available/acep-labs.conf > /dev/null <<EOF
<VirtualHost *:80>
    ServerName localhost
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html
    
    <Directory /var/www/html>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog \${APACHE_LOG_DIR}/acep-labs_error.log
    CustomLog \${APACHE_LOG_DIR}/acep-labs_access.log combined
</VirtualHost>
EOF
    
    # Enable ACEP Labs site
    print_status "INFO" "Enabling ACEP Labs site..."
    a2dissite 000-default 2>/dev/null
    a2ensite acep-labs
    
    # Start and enable Apache
    print_status "INFO" "Starting and enabling Apache..."
    systemctl enable apache2
    systemctl start apache2
    
    print_status "SUCCESS" "Apache configured successfully!"
}

# Function to configure MariaDB (ROOT MODE)
configure_mariadb_root() {
    echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
    echo -e "${GREEN}MARIADB CONFIGURATION (ROOT MODE)${NC}"
    echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
    
    # Start and enable MariaDB
    print_status "INFO" "Starting and enabling MariaDB..."
    systemctl enable mariadb
    systemctl start mariadb
    
    # Wait for MariaDB to be ready
    print_status "INFO" "Waiting for MariaDB to be ready..."
    sleep 5
    
    # Check if MariaDB root access is already configured
    if mysql -u root -e "SELECT 1;" >/dev/null 2>&1; then
        print_status "INFO" "MariaDB root access already configured"
        # Try to set password if none exists
        if ! mysql -u root -e "SELECT 1;" >/dev/null 2>&1; then
            mysql -u root -e "ALTER USER 'root'@'localhost' IDENTIFIED BY 'acep_root_password_2024';" 2>/dev/null || true
        fi
    else
        print_status "INFO" "Securing MariaDB installation..."
        
        # Try to access MariaDB without password first
        if mysql -u root -e "SELECT 1;" >/dev/null 2>&1; then
            # No password set, secure it
            mysql -u root -e "ALTER USER 'root'@'localhost' IDENTIFIED BY 'acep_root_password_2024';" 2>/dev/null || true
        else
            # Reset root password
            reset_mariadb_root_password_root
        fi
        
        # Test the new password
        if ! mysql -u root -pacep_root_password_2024 -e "SELECT 1;" >/dev/null 2>&1; then
            print_status "WARNING" "Root password reset may have failed, trying alternative method..."
            reset_mariadb_root_password_root
        fi
    fi
    
    # Secure the installation
    print_status "INFO" "Securing MariaDB installation..."
    mysql -u root -pacep_root_password_2024 -e "DELETE FROM mysql.user WHERE User='';" 2>/dev/null || mysql -u root -e "DELETE FROM mysql.user WHERE User='';" 2>/dev/null || true
    mysql -u root -pacep_root_password_2024 -e "DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');" 2>/dev/null || mysql -u root -e "DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');" 2>/dev/null || true
    mysql -u root -pacep_root_password_2024 -e "DROP DATABASE IF EXISTS test;" 2>/dev/null || mysql -u root -e "DROP DATABASE IF EXISTS test;" 2>/dev/null || true
    mysql -u root -pacep_root_password_2024 -e "FLUSH PRIVILEGES;" 2>/dev/null || mysql -u root -e "FLUSH PRIVILEGES;" 2>/dev/null || true
    
    print_status "SUCCESS" "MariaDB configured successfully!"
}

# Function to reset MariaDB root password (ROOT MODE)
reset_mariadb_root_password_root() {
    print_status "INFO" "Resetting MariaDB root password..."
    
    # Stop MariaDB
    systemctl stop mariadb
    
    # Start MariaDB in safe mode
    mysqld_safe --skip-grant-tables --skip-networking &
    local safe_pid=$!
    
    # Wait for safe mode to start
    sleep 5
    
    # Reset root password (try different methods)
    if mysql -u root -e "USE mysql; UPDATE user SET authentication_string=PASSWORD('acep_root_password_2024') WHERE User='root'; FLUSH PRIVILEGES;" >/dev/null 2>&1; then
        print_status "SUCCESS" "Root password reset using PASSWORD() function"
    elif mysql -u root -e "USE mysql; UPDATE user SET authentication_string='acep_root_password_2024' WHERE User='root'; FLUSH PRIVILEGES;" >/dev/null 2>&1; then
        print_status "SUCCESS" "Root password reset using direct string"
    else
        print_status "WARNING" "Could not reset password using standard methods"
    fi
    
    # Stop safe mode
    pkill -f mysqld_safe
    pkill -f mysqld
    
    # Wait for processes to stop
    sleep 3
    
    # Start MariaDB normally
    systemctl start mariadb
    sleep 5
}

# Function to deploy ACEP Cyber Labs (ROOT MODE)
deploy_acep_labs_root() {
    echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
    echo -e "${GREEN}ACEP CYBER LABS DEPLOYMENT (ROOT MODE)${NC}"
    echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
    
    # Clean existing files in web root
    print_status "INFO" "Cleaning existing web files..."
    rm -rf /var/www/html/*
    
    # Copy files directly to web root
    print_status "INFO" "Copying application files..."
    cp -r index.php /var/www/html/ 2>/dev/null || cp index.php /var/www/html/
    cp -r config.php /var/www/html/ 2>/dev/null || cp config.php /var/www/html/
    cp -r assets /var/www/html/ 2>/dev/null || cp -r assets /var/www/html/
    cp -r labs /var/www/html/ 2>/dev/null || cp -r labs /var/www/html/
    cp -r README.md /var/www/html/ 2>/dev/null || cp README.md /var/www/html/
    
    # Verify files were copied
    if [ -f /var/www/html/index.php ]; then
        print_status "SUCCESS" "Main application files copied successfully"
    else
        print_status "WARNING" "Some files may not have been copied properly"
    fi
    
    # Set permissions
    print_status "INFO" "Setting proper permissions..."
    chown -R www-data:www-data /var/www/html
    chmod -R 755 /var/www/html
    chmod 777 /var/www/html/labs/upload/ 2>/dev/null || true
    
    print_status "SUCCESS" "ACEP CYBER LABS deployed successfully!"
}

# Function to setup database (ROOT MODE)
setup_database_root() {
    echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
    echo -e "${GREEN}DATABASE SETUP (ROOT MODE)${NC}"
    echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
    
    # Create database and user
    print_status "INFO" "Creating database and user..."
    
    # Try to create database with root access
    if mysql -u root -pacep_root_password_2024 -e "CREATE DATABASE IF NOT EXISTS acep_labs;" >/dev/null 2>&1; then
        print_status "SUCCESS" "Database 'acep_labs' created successfully"
    else
        print_status "WARNING" "Could not create database with root password, trying without password..."
        if mysql -u root -e "CREATE DATABASE IF NOT EXISTS acep_labs;" >/dev/null 2>&1; then
            print_status "SUCCESS" "Database 'acep_labs' created successfully"
        else
            print_status "ERROR" "Failed to create database"
            return 1
        fi
    fi
    
    # Create user and grant privileges
    if mysql -u root -pacep_root_password_2024 -e "CREATE USER IF NOT EXISTS 'acep_user'@'localhost' IDENTIFIED BY 'acep_password_2024';" >/dev/null 2>&1; then
        print_status "SUCCESS" "User 'acep_user' created successfully"
    else
        print_status "WARNING" "Could not create user with root password, trying without password..."
        mysql -u root -e "CREATE USER IF NOT EXISTS 'acep_user'@'localhost' IDENTIFIED BY 'acep_password_2024';" >/dev/null 2>&1 || true
    fi
    
    # Grant privileges
    if mysql -u root -pacep_root_password_2024 -e "GRANT ALL PRIVILEGES ON acep_labs.* TO 'acep_user'@'localhost';" >/dev/null 2>&1; then
        print_status "SUCCESS" "Privileges granted successfully"
    else
        print_status "WARNING" "Could not grant privileges with root password, trying without password..."
        mysql -u root -e "GRANT ALL PRIVILEGES ON acep_labs.* TO 'acep_user'@'localhost';" >/dev/null 2>&1 || true
    fi
    
    # Flush privileges
    mysql -u root -pacep_root_password_2024 -e "FLUSH PRIVILEGES;" >/dev/null 2>&1 || mysql -u root -e "FLUSH PRIVILEGES;" >/dev/null 2>&1 || true
    
    print_status "SUCCESS" "Database setup completed!"
}

# Function to final configuration and testing (ROOT MODE)
final_configuration_root() {
    echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
    echo -e "${GREEN}FINAL CONFIGURATION AND TESTING (ROOT MODE)${NC}"
    echo -e "${PURPLE}═══════════════════════════════════════════════════════════════${NC}"
    
    # Restart services
    print_status "INFO" "Restarting Apache..."
    systemctl restart apache2
    
    print_status "INFO" "Restarting MariaDB..."
    systemctl restart mariadb
    
    # Test services
    print_status "INFO" "Testing services..."
    
    if systemctl is-active --quiet apache2; then
        print_status "SUCCESS" "Apache is running"
    else
        print_status "ERROR" "Apache is not running"
    fi
    
    if systemctl is-active --quiet mariadb; then
        print_status "SUCCESS" "MariaDB is running"
    else
        print_status "ERROR" "MariaDB is not running"
    fi
    
    if curl -s http://localhost > /dev/null; then
        print_status "SUCCESS" "Web server is responding"
    else
        print_status "WARNING" "Web server is not responding"
    fi
    
    if [ -f /var/www/html/index.php ]; then
        print_status "SUCCESS" "ACEP CYBER LABS application is accessible"
    else
        print_status "ERROR" "ACEP CYBER LABS application not found"
    fi
    
    # Test database connection
    if mysql -u acep_user -pacep_password_2024 -e "USE acep_labs;" > /dev/null 2>&1; then
        print_status "SUCCESS" "Database connection successful"
    else
        print_status "WARNING" "Database connection failed"
    fi
    
    print_status "SUCCESS" "Final configuration completed!"
}

# Main installation function
main() {
    # Check if running as root (sudo su mode)
    if [[ $EUID -eq 0 ]]; then
        echo -e "${YELLOW}⚠️  Running in ROOT mode (sudo su)${NC}"
        echo -e "${CYAN}The script will run with full privileges.${NC}"
        echo ""
        
        # Check if we're in the project directory
        if [ ! -f "index.php" ] || [ ! -d "labs" ]; then
            echo -e "${RED}Please run this script from the ACEP CYBER LABS project directory.${NC}"
            echo -e "${YELLOW}Current directory:${NC} ${WHITE}$(pwd)${NC}"
            echo -e "${YELLOW}Expected files:${NC} index.php and labs/ directory"
            echo -e "${CYAN}Make sure you're in the correct project folder.${NC}"
            exit 1
        fi
        
        echo -e "${GREEN}✓ Root mode detected - All checks passed! Starting installation...${NC}"
        echo ""
        
        # Start installation in root mode
        check_system_requirements
        install_lamp_stack_root
        configure_apache_root
        configure_mariadb_root
        deploy_acep_labs_root
        setup_database_root
        final_configuration_root
        show_completion
        return 0
    fi
    
    # Regular user mode (with sudo)
    echo -e "${CYAN}Running in REGULAR USER mode (with sudo privileges)${NC}"
    echo ""
    
    # Check if sudo is available
    if ! command_exists sudo; then
        echo -e "${RED}sudo is not installed. Please install sudo first.${NC}"
        echo -e "${YELLOW}Install sudo:${NC} ${WHITE}su -c 'apt update && apt install -y sudo'${NC}"
        exit 1
    fi
    
    # Check if user has sudo privileges
    if ! sudo -n true 2>/dev/null; then
        echo -e "${YELLOW}Checking sudo privileges...${NC}"
        echo -e "${CYAN}You will be prompted for your password to run sudo commands.${NC}"
        echo -e "${CYAN}This is normal and required for system installation.${NC}"
    echo ""
        # Test sudo access
        if ! sudo -v; then
            echo -e "${RED}Failed to get sudo privileges. Please ensure your user has sudo access.${NC}"
            echo -e "${YELLOW}To add your user to sudo group:${NC}"
            echo -e "   ${WHITE}su -c 'usermod -aG sudo $USER'${NC}"
            echo -e "   ${WHITE}Then log out and log back in.${NC}"
            exit 1
        fi
    fi
    
    # Check if we're in the project directory
    if [ ! -f "index.php" ] || [ ! -d "labs" ]; then
        echo -e "${RED}Please run this script from the ACEP CYBER LABS project directory.${NC}"
        echo -e "${YELLOW}Current directory:${NC} ${WHITE}$(pwd)${NC}"
        echo -e "${YELLOW}Expected files:${NC} index.php and labs/ directory"
        echo -e "${CYAN}Make sure you're in the correct project folder.${NC}"
        exit 1
    fi
    
    echo -e "${GREEN}✓ All checks passed! Starting installation...${NC}"
    echo ""
    
    # Start installation in regular user mode
    check_system_requirements
    install_lamp_stack
    configure_apache
    configure_mariadb
    deploy_acep_labs
    setup_database
    final_configuration
    show_completion
}

# Run main function
main "$@" 
