#!/bin/bash
FASTAPI_PORT=9010

# Update and install dependencies
sudo apt update && sudo apt upgrade -y
sudo apt install python3-pip python3-venv cmake libgl1 -y

# Clone repo
git clone https://github.com/recluzegeek/deepscan-api
cd deepscan-api

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
