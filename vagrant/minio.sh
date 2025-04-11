#!/bin/bash
ROOT_USER="rgk_admin"
ROOT_PASSWORD="minio_secret_key_change_me"
MINIO_DEPLOYMENT_ALIAS="rgk_minio"
NORMAL_USERNAME="laravel"
NORMAL_USER_PASSWORD="%@typicalstrongpassword:)"
BUCKET_NAME="deepscan"
PORT=9001

sudo dnf update -y
sudo dnf install wget -y

# Install MinIO
wget https://dl.min.io/server/minio/release/linux-amd64/archive/minio-20250408154124.0.0-1.x86_64.rpm -O minio.rpm
sudo dnf install minio.rpm -y

# Install MinIO cli
wget https://dl.min.io/client/mc/release/linux-amd64/mcli-20250408153949.0.0-1.x86_64.rpm -O mcli.rpm
sudo dnf install mcli.rpm -y

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

/usr/local/bin/mcli alias set $MINIO_DEPLOYMENT_ALIAS/ http://$(hostname -I | cut -d' ' -f2):9000 $ROOT_USER $ROOT_PASSWORD

# add less privileged minio user
## mc admin user add ALIAS ACCESSKEY SECRETKEY
/usr/local/bin/mcli admin user add $MINIO_DEPLOYMENT_ALIAS $NORMAL_USERNAME $NORMAL_USER_PASSWORD

# attach readwrite policy to the user
## mc admin policy attach ALIAS readwrite --user=USERNAME
# TODO: attach more strict policy, restricting access to specific bucket
/usr/local/bin/mcli admin policy attach $MINIO_DEPLOYMENT_ALIAS readwrite --user=$NORMAL_USERNAME
/usr/local/bin/mcli admin user list $MINIO_DEPLOYMENT_ALIAS

# Create bucket
/usr/local/bin/mcli mb --region=us-east-1 $MINIO_DEPLOYMENT_ALIAS/$BUCKET_NAME

# Show status
sudo systemctl status minio
