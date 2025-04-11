#!/bin/bash
REDIS_PASSWORD="<password-needs-to-be-changed>"
# LARAVEL_VM_IP="$(getent hosts $1 | awk -F' ' '{print $1}')"
# REDIS_INTERFACE_NETWORK_OCTETS="$(hostname -I | awk '{print $2}' | cut -d'.' -f1-3).0/24"


sudo dnf update -y
sudo dnf install redis -y
sudo systemctl enable redis
sudo systemctl start redis
sudo systemctl status redis
sudo systemctl start firewalld
sudo systemctl enable firewalld

# securing redis installation

## set a password for Redis
echo "+++++++++++++++++Changed the redis password+++++++++++++++++"
sed -i "s/# requirepass foobared/requirepass $REDIS_PASSWORD/" /etc/redis/redis.conf

## disable remote access - bind to internal network
echo "+++++++++++++++++Changed the redis bind address+++++++++++++++++"
sudo sed -i "s/bind 127.0.0.1 -::1/bind $(hostname -I | awk -F' ' '{print $2}') 127.0.0.1/" /etc/redis/redis.conf

sudo systemctl restart redis
sudo systemctl status redis

## network level security
echo "+++++++++++++++++Added firewalld rules+++++++++++++++++"
sudo firewall-cmd --zone=public \
    --add-rich-rule="rule family='ipv4' source address='192.168.56.20' port protocol='tcp' port='6379' accept" --permanent

sudo firewall-cmd --reload
