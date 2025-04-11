#!/bin/bash
ROOT_USER="rgkadmin"
ROOT_PASSWORD="minio-secret-key-change-me"
PORT=9001

sudo dnf update -y
sudo dnf install wget -y

# Install MinIO
wget https://dl.min.io/server/minio/release/linux-amd64/archive/minio-20250408154124.0.0-1.x86_64.rpm -O minio.rpm
sudo dnf install minio.rpm -y

# Create data directory and user
sudo mkdir -p /mnt/data
sudo groupadd -r minio-user
sudo useradd -M -r -g minio-user minio-user
sudo chown minio-user:minio-user /mnt/data

# Ensure default env directory exists
sudo mkdir -p /etc/default

# Configure MinIO
sudo tee /etc/default/minio > /dev/null <<EOT
MINIO_ROOT_USER=$ROOT_USER
MINIO_ROOT_PASSWORD=$ROOT_PASSWORD
MINIO_VOLUMES="/mnt/data"
MINIO_OPTS="--console-address :$PORT"
EOT

# Start and enable MinIO service
sudo systemctl daemon-reexec
sudo systemctl daemon-reload
sudo systemctl enable --now minio

# Show status
sudo systemctl status minio
