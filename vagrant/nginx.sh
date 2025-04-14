#!/bin/bash

PHP_FPM_POOL_USERNAME="laravel_deepscan"
PHP_FPM_POOL_GROUPNAME="laravel_deepscan"

APP_ENV="local"
LARAVEL_DB_USERNAME='laravel'
LARAVEL_DB_PASSWORD='admin123'
LARAVEL_DB_NAME='deepscan'

MINIO_ACCESS_AND_SECRETS_LOCATION="/tmp/secrets/minio-keys.txt"
SECRETS=$(cat $MINIO_ACCESS_AND_SECRETS_LOCATION)
MINIO_ACCESS_KEY=$(echo "$SECRETS" | head -n 1) # first line is of access key
MINIO_SECRET_KEY=$(echo "$SECRETS" | tail -n 1) # second line is of secret key
FRAMES_BUCKET_NAME="deepscan-frames"
GRADCAM_FRAMES_BUCKET_NAME="gradcam-deepscan-frames"
FASTAPI_URL=$(getent hosts $1 | cut -d' ' -f1) # translates vm name to ip address via host file

REDIS_PASSWORD="<password-needs-to-be-changed>"


## adding php repository
sudo LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php -y
sudo apt update && sudo apt upgrade -y
sudo apt install nginx -y

# installing php along with common extensions
sudo apt install php8.4-cli php8.4-fpm unzip -y
sudo apt install php8.4-common php8.4-{mysql,bcmath,bz2,curl,gd,gmp,intl,mbstring,opcache,readline,xml,zip} -y

## configuring php8.4-fpm with nginx, inspired from
### https://www.digitalocean.com/community/tutorials/php-fpm-nginx

### creating separate php-fpm pool
sudo groupadd $PHP_FPM_POOL_USERNAME
sudo useradd -g $PHP_FPM_POOL_USERNAME $PHP_FPM_POOL_GROUPNAME

sudo tee /etc/php/8.4/fpm/pool.d/$PHP_FPM_POOL_USERNAME.conf > /dev/null <<EOT
[$PHP_FPM_POOL_USERNAME]
user = www-data
group = www-data
listen = /var/run/php8.4-fpm-$PHP_FPM_POOL_USERNAME.sock
listen.owner = www-data
listen.group = www-data
php_admin_value[disable_functions] = exec,passthru,shell_exec,system
php_admin_flag[allow_url_fopen] = off
; Choose how the process manager will control the number of child processes. 
pm = dynamic 
pm.max_children = 75 
pm.start_servers = 10 
pm.min_spare_servers = 5 
pm.max_spare_servers = 20 
pm.process_idle_timeout = 10s
EOT

sudo systemctl enable php8.4-fpm
sudo systemctl start php8.4-fpm
sudo systemctl status php8.4-fpm

# installing composer
curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php
sudo php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer

# installing nodejs
## taken from, https://nodejs.org/en/download
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.2/install.sh | bash
\. "$HOME/.nvm/nvm.sh"
nvm install 22
node -v
nvm current
npm -v

# ++++++++++++++++++++++++++++++Code Cloning++++++++++++++++++++++++++++

## cloning deepscan
git clone https://github.com/recluzegeek/deepscan-web /tmp/deepscan-web
cd /tmp/deepscan-web

composer install
npm install
npm run build

cp .env.example .env

# modify environment variables
sed -i "s/APP_ENV=.*/APP_ENV=$APP_ENV/" .env
# sed -i "s/APP_DEBUG=.*/APP_DEBUG=false/" .env
sed -i "s/APP_URL=.*/APP_URL=http:\/\/$(hostname -I | cut -d' ' -f2)/" .env
sed -i "s/MODEL_API_URL=.*/MODEL_API_URL=http:\/\/$(getent hosts $3 | cut -d' ' -f1)/" .env
sed -i "s/DB_HOST=.*/DB_HOST=$(getent hosts $1 | cut -d' ' -f1)/" .env
sed -i "s/DB_DATABASE=.*/DB_DATABASE=$LARAVEL_DB_NAME/" .env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=$LARAVEL_DB_USERNAME/" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$LARAVEL_DB_PASSWORD/" .env

sed -i "s/REDIS_HOST=.*/REDIS_HOST=$(getent hosts $2 | cut -d' ' -f1)/" .env
sed -i "s/REDIS_PASSWORD=.*/REDIS_PASSWORD=$REDIS_PASSWORD/" .env

php artisan key:generate
php artisan migrate

sudo mv /tmp/deepscan-web /var/www/html/deepscan-web

# Set ownership
sudo chown -R www-data:www-data /var/www/html/deepscan-web/storage
sudo chown -R www-data:www-data /var/www/html/deepscan-web/bootstrap/cache

# Set directory permissions to 2775 (setgid bit + rwxrwxr-x)
sudo find /var/www/html/deepscan-web/storage -type d -exec chmod 2775 {} +
sudo find /var/www/html/deepscan-web/bootstrap/cache -type d -exec chmod 2775 {} +

# Set file permissions to 0664 (rw-rw-r--)
sudo find /var/www/html/deepscan-web/storage -type f -exec chmod 664 {} +
sudo find /var/www/html/deepscan-web/bootstrap/cache -type f -exec chmod 664 {} +


### configuring nginx
sudo tee /etc/nginx/sites-available/$PHP_FPM_POOL_USERNAME > /dev/null <<EOT
server {
    listen 80;
    server_name _; # listen on all interfaces

    root /var/www/html/deepscan-web/public;
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.php index.html index.htm;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php8.4-fpm-$PHP_FPM_POOL_USERNAME.sock;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOT

sudo ln -s /etc/nginx/sites-available/$PHP_FPM_POOL_USERNAME /etc/nginx/sites-enabled/
sudo rm /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl restart nginx
sudo systemctl status nginx
sudo systemctl restart php8.4-fpm
sudo systemctl status php8.4-fpm

# we will enable and start the service, once we have the redis server up and running. This is done
# using vagrant after trigger which will ssh into redis and web VMs to add the necessary firewall rules
# and start the Laravel Horizon process.

sudo tee /etc/systemd/system/horizon.service > /dev/null <<EOT
[Unit]
Description=Laravel Horizon
After=network.target

[Service]
User=vagrant
ExecStart=/usr/bin/php /var/www/html/deepscan-web/artisan horizon
Restart=always

[Install]
WantedBy=multi-user.target
EOT
