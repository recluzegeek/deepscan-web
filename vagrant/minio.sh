#!/bin/bash
# This script installs and configures MinIO on a CentOS/RHEL system.
# It sets up a MinIO server with specified root user and password, creates
# a data directory, and configures the MinIO service to start on boot.
# It also installs the MinIO client (mc) and sets up a bucket for storing
# frames and gradcam frames. Secrets and access keys are shared across vms,
# and a policy is created for read and write access to the specified buckets.

ROOT_USER="rgk_admin"
ROOT_PASSWORD="minio_secret_key_change_me"
MINIO_DEPLOYMENT_ALIAS="deepscan_minio"
FRAMES_BUCKET_NAME="deepscan-frames"
GRADCAM_FRAMES_BUCKET_NAME="deepscan-gradcam-frames"
MINIO_ACCESS_AND_SECRETS_LOCATION="/tmp/secrets/minio-keys.txt"
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

# setup minio alias
/usr/local/bin/mcli alias set $MINIO_DEPLOYMENT_ALIAS http://$(hostname -I | cut -d' ' -f2):9000 $ROOT_USER $ROOT_PASSWORD

# create policy for read and write access to specific buckets
tee deepscan-readwrite.json > /dev/null <<EOT
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Action": [
        "s3:GetBucketLocation",
        "s3:ListBucket"
      ],
      "Effect": "Allow",
      "Resource": [
        "arn:aws:s3:::${FRAMES_BUCKET_NAME}",
        "arn:aws:s3:::${GRADCAM_FRAMES_BUCKET_NAME}"
      ]
    },
    {
      "Action": [
        "s3:GetObject",
        "s3:PutObject",
        "s3:DeleteObject"
      ],
      "Effect": "Allow",
      "Resource": [
        "arn:aws:s3:::${FRAMES_BUCKET_NAME}/*",
        "arn:aws:s3:::${GRADCAM_FRAMES_BUCKET_NAME}/*"
      ]
    }
  ]
}
EOT

# generate access and secret keys
## inspired from, https://min.io/docs/minio/linux/reference/minio-mc-admin/mc-admin-accesskey-create.html
/usr/local/bin/mcli admin accesskey create $MINIO_DEPLOYMENT_ALIAS \
     --policy deepscan-readwrite.json \
     --name "deepscan" \
     --description "Keys for Deepscan web and FastAPI for frames storage and access remotely" \
     | awk -F": " '{print $2}' | head -n 2 > $MINIO_ACCESS_AND_SECRETS_LOCATION

# Create bucket
/usr/local/bin/mcli mb --region=us-east-1 $MINIO_DEPLOYMENT_ALIAS/$FRAMES_BUCKET_NAME
/usr/local/bin/mcli mb --region=us-east-1 $MINIO_DEPLOYMENT_ALIAS/$GRADCAM_FRAMES_BUCKET_NAME

# Show status
sudo systemctl status minio
