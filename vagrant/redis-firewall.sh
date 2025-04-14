#!/bin/bash
# This script configures the firewall on the Redis VM to allow access from the web VM.
# It ssh into redis and web VMs to add the necessary firewall rules and start the Laravel Horizon process.

REDIS_VM=$1
WEB_VM=$2

WEB01_IP=$(getent hosts "$WEB_VM" | cut -d' ' -f1)

echo "[INFO] Adding Redis firewall rule to allow $WEB_VM ($WEB01_IP)..."

vagrant ssh "$REDIS_VM" -c "sudo firewall-cmd --zone=public --add-rich-rule='rule family=\"ipv4\" source address=\"$WEB01_IP\" port protocol=\"tcp\" port=\"6379\" accept' --permanent && sudo firewall-cmd --reload"

if [[ $? -ne 0 ]]; then
  echo "[ERROR] Failed to configure firewall on $REDIS_VM"
  exit 1
else
  echo "[SUCCESS] Firewall rule added on $REDIS_VM for $WEB_VM"
fi

echo "[INFO] Firing up Laravel Horizon on $WEB_VM..."
vagrant ssh "$WEB_VM" --no-tty -c "sudo systemctl enable horizon.service && sudo systemctl start horizon.service && sudo systemctl status horizon.service"

# # Immediately exit after starting the background process
# echo "[SUCCESS] Script completed."
# exit 0