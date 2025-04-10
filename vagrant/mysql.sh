#!/bin/bash

# This script sets up a MySQL database for a Laravel application on a CentOS 7 VM.
ROOT_DATABASE_PASS='<password-needs-to-be-changed>'
LARAVEL_USERNAME='laravel'
LARAVEL_PASSWORD='admin123'
LARAVEL_DB_NAME='deepscan'
LARAVEL_VM_NAME="$1"  # Argument passed to the script

# Update system and install required packages
sudo yum update -y
sudo yum install git zip unzip -y
sudo yum install mariadb-server -y

# Start and enable MariaDB
sudo systemctl start mariadb
sudo systemctl enable mariadb

# Set the MySQL root password and clean up any unnecessary user accounts
# similar to the mysql_secure_installation script
sudo mysqladmin -u root password "$ROOT_DATABASE_PASS"
sudo mysql -u root -p"$ROOT_DATABASE_PASS" -e "DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1')"
sudo mysql -u root -p"$ROOT_DATABASE_PASS" -e "DELETE FROM mysql.user WHERE User=''"
sudo mysql -u root -p"$ROOT_DATABASE_PASS" -e "DELETE FROM mysql.db WHERE Db='test' OR Db='test\_%'"
sudo mysql -u root -p"$ROOT_DATABASE_PASS" -e "FLUSH PRIVILEGES"

# Create the Laravel database
sudo mysql -u root -p"$ROOT_DATABASE_PASS" -e "CREATE DATABASE IF NOT EXISTS $LARAVEL_DB_NAME"

# Grant privileges to Laravel database user
sudo mysql -u root -p"$ROOT_DATABASE_PASS" -e "GRANT ALL PRIVILEGES ON $LARAVEL_DB_NAME.* TO '$LARAVEL_USERNAME'@'$LARAVEL_VM_NAME' IDENTIFIED BY '$LARAVEL_PASSWORD'"

# Flush privileges
sudo mysql -u root -p"$ROOT_DATABASE_PASS" -e "FLUSH PRIVILEGES"

# Restart MariaDB service to apply changes
sudo systemctl restart mariadb

# Start and configure firewall to allow MySQL access on port 3306
sudo systemctl start firewalld
sudo systemctl enable firewalld
sudo firewall-cmd --zone=public --add-port=3306/tcp --permanent
sudo firewall-cmd --reload

# Verify the firewall configuration
sudo firewall-cmd --list-all

# Restart MariaDB once more to ensure the firewall changes take effect
sudo systemctl restart mariadb
