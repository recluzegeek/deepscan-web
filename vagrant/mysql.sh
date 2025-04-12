#!/bin/bash

# This script sets up a MySQL database for a Laravel application on a CentOS 7 VM.
ROOT_DATABASE_PASS='<password-needs-to-be-changed>'
LARAVEL_DB_USERNAME='laravel'
LARAVEL_DB_PASSWORD='admin123'
LARAVEL_DB_NAME='deepscan'
LARAVEL_VM_NAME="$1"  # Argument passed to the script

# Update system and install mariadb==11.8
sudo tee /etc/yum.repos.d/MariaDB.repo > /dev/null <<EOF
[mariadb]
name = MariaDB
baseurl = https://mirror.mariadb.org/yum/11.8/rhel9-amd64/
gpgkey=https://yum.mariadb.org/RPM-GPG-KEY-MariaDB
gpgcheck=1
enabled=1
EOF

sudo dnf update -y
sudo dnf install git zip unzip -y
sudo dnf install MariaDB-server -y

# Start and enable MariaDB
sudo systemctl start mariadb
sudo systemctl enable mariadb

# Set the MySQL root password and clean up any unnecessary user accounts
# similar to the mysql_secure_installation script
sudo mariadb-admin -u root password "$ROOT_DATABASE_PASS"

# Secure MariaDB using mariadb shell
sudo mariadb -u root -p"$ROOT_DATABASE_PASS" <<SQL
-- Remove remote root users
DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');
-- Remove anonymous users
DELETE FROM mysql.user WHERE User='';
-- Remove test databases
DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';
-- Apply changes
FLUSH PRIVILEGES;
SQL

# Create Laravel database
sudo mariadb -u root -p"$ROOT_DATABASE_PASS" <<SQL
CREATE DATABASE IF NOT EXISTS $LARAVEL_DB_NAME;
GRANT ALL PRIVILEGES ON $LARAVEL_DB_NAME.* TO '$LARAVEL_DB_USERNAME'@'$LARAVEL_VM_NAME' IDENTIFIED BY '$LARAVEL_DB_PASSWORD';
FLUSH PRIVILEGES;
SQL

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
