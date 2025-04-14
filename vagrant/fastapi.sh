#!/bin/bash
# This script installs and configures a FastAPI application on a Ubuntu 22.04 Jammy Jellyfish system.
# It sets up a Python virtual environment, installs dependencies, and creates a systemd service for 
# the FastAPI application. It is tightly integrated with MinIO server for downloading frames 
# and a Laravel web application to send back inference.

MINIO_ACCESS_AND_SECRETS_LOCATION="/tmp/secrets/minio-keys.txt"
SECRETS=$(cat $MINIO_ACCESS_AND_SECRETS_LOCATION)
MINIO_ACCESS_KEY=$(echo "$SECRETS" | head -n 1) # first line is of access key
MINIO_SECRET_KEY=$(echo "$SECRETS" | tail -n 1) # second line is of secret key
FRAMES_BUCKET_NAME="deepscan-frames"
GRADCAM_FRAMES_BUCKET_NAME="gradcam-deepscan-frames"
LARAVEL_WEB_URL=$(getent hosts $1 | cut -d' ' -f1) # translates vm name to ip address via host file
FASTAPI_PORT=9010

# Update and install dependencies
sudo apt update && sudo apt upgrade -y
sudo apt install python3-pip python3-venv cmake libgl1 -y

# Clone repo
git clone https://github.com/recluzegeek/deepscan-api
cd deepscan-api

## configure api
cp config/settings.example.yaml config/settings.yaml

sed -i 's/^  endpoint:.*/  endpoint: "$(hostname -I | cut -d' ' -f2)" /' config/settings.yaml # minio endpoint
sed -i 's/^  access_key:.*/  access_key: "$MINIO_ACCESS_KEY" /' config/settings.yaml
sed -i 's/^  secret_key:.*/  secret_key: "$MINIO_SECRET_KEY" /' config/settings.yaml
sed -i 's/^  frames_bucket:.*/  frames_bucket: "$FRAMES_BUCKET_NAME" /' config/settings.yaml
sed -i 's/^  gradcam_frames_bucket:.*/  gradcam_frames_bucket: "$GRADCAM_FRAMES_BUCKET_NAME" /' config/settings.yaml
sed -i 's/^  base_url:.*/  base_url: "$LARAVEL_WEB_URL" /' config/settings.yaml   # laravel-web app

# Add swap space
sudo fallocate -l 4G /swapfile
sudo chmod 600 /swapfile
sudo mkswap /swapfile
sudo swapon /swapfile
echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab
free -h

# Python environment setup
python3 -m venv .venv
source .venv/bin/activate
pip install -r requirements.txt

# Create log files with proper permissions
sudo touch /var/log/fastapi.log /var/log/fastapi.err
sudo chown vagrant:vagrant /var/log/fastapi.log /var/log/fastapi.err

# Create systemd service file
sudo bash -c "cat > /etc/systemd/system/fastapi.service" <<EOT
[Unit]
Description=FastAPI Service
After=network.target

[Service]
User=vagrant
WorkingDirectory=/home/vagrant/deepscan-api
ExecStart=/home/vagrant/deepscan-api/.venv/bin/fastapi run main.py --host 0.0.0.0 --port $FASTAPI_PORT
Restart=always
StandardOutput=append:/var/log/fastapi.log
StandardError=append:/var/log/fastapi.err

[Install]
WantedBy=multi-user.target
EOT

# Enable and start the service
sudo systemctl daemon-reexec
sudo systemctl daemon-reload
sudo systemctl enable --now fastapi
sudo systemctl status fastapi --no-pager
