# DEPLOYMENT & SETUP GUIDE - Ship Spare Parts Store

## PRODUCTION DEPLOYMENT - Ubuntu 22.04 + Nginx + MySQL

### Phase 1: Server Setup

#### 1. Initial Server Configuration
```bash
#!/bin/bash
set -e

# Update system
sudo apt update && sudo apt upgrade -y
sudo apt install -y curl wget git zip unzip software-properties-common

# Add repositories
sudo add-apt-repository ppa:ondrej/php -y
sudo add-apt-repository ppa:ondrej/nginx-mainline -y
sudo apt update

# Install PHP 8.3
sudo apt install -y php8.3-fpm php8.3-cli php8.3-mysql php8.3-redis \
    php8.3-xml php8.3-mbstring php8.3-curl php8.3-gd php8.3-imagick \
    php8.3-bcmath php8.3-zip php8.3-intl php8.3-dev

# Install Nginx
sudo apt install -y nginx

# Install MySQL
sudo apt install -y mysql-server

# Install Redis
sudo apt install -y redis-server

# Install Supervisor (for queues)
sudo apt install -y supervisor

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Install Node.js (for asset compilation)
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Install SSL Certbot
sudo apt install -y certbot python3-certbot-nginx
```

#### 2. Create Application User
```bash
# Create deployment user
sudo useradd -m -s /bin/bash deploy
sudo usermod -aG sudo deploy
sudo usermod -aG www-data deploy

# Setup SSH for deploy user
sudo -u deploy mkdir -p /home/deploy/.ssh
sudo -u deploy ssh-keygen -t ed25519 -N "" -f /home/deploy/.ssh/id_ed25519

# Add your public key to authorized_keys
echo "your-public-key" | sudo tee /home/deploy/.ssh/authorized_keys
sudo chmod 600 /home/deploy/.ssh/authorized_keys
```

#### 3. Database Setup
```bash
# Create database and user
sudo mysql -u root << EOF
CREATE DATABASE ship_spare_parts CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'app_user'@'localhost' IDENTIFIED BY 'SecurePassword123!';
GRANT ALL PRIVILEGES ON ship_spare_parts.* TO 'app_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
EOF

# Optimize MySQL
sudo tee /etc/mysql/conf.d/optimize.cnf > /dev/null <<EOF
[mysqld]
max_connections = 200
default_storage_engine = InnoDB
innodb_buffer_pool_size = 2G
innodb_log_file_size = 512M
query_cache_size = 0
query_cache_type = 0
character_set_server = utf8mb4
collation_server = utf8mb4_unicode_ci

# Full-text search optimization
ft_min_word_len = 2
ft_max_word_len = 84
ft_stopword_file = (builtin)
EOF

sudo systemctl restart mysql
```

#### 4. PHP-FPM Configuration
```bash
# Configure PHP-FPM pool
sudo tee /etc/php/8.3/fpm/pool.d/shipparts.conf > /dev/null <<EOF
[shipparts]
user = www-data
group = www-data
listen = /var/run/php-fpm-shipparts.sock
listen.owner = www-data
listen.group = www-data
listen.mode = 0666

pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 500

slowlog = /var/log/php-fpm-shipparts-slow.log
request_slowlog_timeout = 10s
request_terminate_timeout = 60s

env[PATH] = /usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin
env[TMP] = /tmp
env[TMPDIR] = /tmp
env[TEMP] = /tmp
EOF

# Update PHP settings
sudo tee -a /etc/php/8.3/fpm/php.ini > /dev/null <<EOF
upload_max_filesize = 100M
post_max_size = 100M
memory_limit = 512M
max_execution_time = 300
opcache.enable = 1
opcache.memory_consumption = 256
opcache.max_accelerated_files = 20000
opcache.validate_timestamps = 0
EOF

sudo systemctl restart php8.3-fpm
```

#### 5. Nginx Configuration
```bash
# Create Nginx server block
sudo tee /etc/nginx/sites-available/shipparts > /dev/null <<'EOF'
upstream laravel_backend {
    server unix:/var/run/php-fpm-shipparts.sock;
}

# Redirect HTTP to HTTPS
server {
    listen 80;
    listen [::]:80;
    server_name shipparts.example.com www.shipparts.example.com;

    location /.well-known/acme-challenge/ {
        root /var/www/shipparts/public;
    }

    location / {
        return 301 https://$server_name$request_uri;
    }
}

# HTTPS Server Block
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name shipparts.example.com;
    root /var/www/shipparts/public;
    index index.php;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/shipparts.example.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/shipparts.example.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 10m;

    # Security Headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';" always;

    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1000;
    gzip_types text/plain text/css text/xml text/javascript application/json application/javascript;

    # Rate Limiting
    limit_req_zone $binary_remote_addr zone=api:10m rate=100r/m;
    limit_req_zone $binary_remote_addr zone=login:10m rate=5r/m;

    # Logging
    access_log /var/log/nginx/shipparts-access.log combined;
    error_log /var/log/nginx/shipparts-error.log warn;

    # Static Files
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff2?)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Laravel public paths
    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass laravel_backend;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }

    # Hide .env and other sensitive files
    location ~ /\. {
        deny all;
    }

    # Laravel routing
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
}

# Rate limiting - login endpoint
server {
    listen 443 ssl http2;
    location ~ ^/api/auth/login {
        limit_req zone=login burst=10 nodelay;
        fastcgi_pass laravel_backend;
    }
}

# Rate limiting - API endpoints
server {
    listen 443 ssl http2;
    location ~ ^/api/ {
        limit_req zone=api burst=200 nodelay;
        fastcgi_pass laravel_backend;
    }
}
EOF

# Enable site
sudo ln -sf /etc/nginx/sites-available/shipparts /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl restart nginx
```

#### 6. SSL Certificate
```bash
# Generate Let's Encrypt certificate
sudo certbot certonly --nginx -d shipparts.example.com -d www.shipparts.example.com

# Auto-renewal (already enabled with certbot)
sudo systemctl enable certbot.timer
```

---

### Phase 2: Application Setup

#### 1. Clone Repository
```bash
cd /var/www
sudo git clone https://github.com/yourrepo/shipparts.git shipparts
sudo chown -R deploy:www-data shipparts
cd shipparts
```

#### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install Node dependencies
npm install
npm run build  # Compile assets

# Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

#### 3. Configure Environment
```bash
# Copy .env file
cp .env.example .env

# Generate application key
php artisan key:generate

# Update .env with production values
# DB_HOST, DB_USERNAME, DB_PASSWORD
# REDIS_HOST, REDIS_PORT
# APP_DEBUG=false
# APP_ENV=production
```

#### 4. Database Migrations & Seeding
```bash
# Run migrations
php artisan migrate --force

# Seed initial data (brands, categories, vessel types, FAQs)
php artisan db:seed

# Create admin user
php artisan tinker
# Inside tinker:
# User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password'), 'role' => 'admin'])
```

#### 5. Setup Supervisor for Queues
```bash
# Create supervisor config
sudo tee /etc/supervisor/conf.d/shipparts-worker.conf > /dev/null <<EOF
[program:shipparts-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/shipparts/artisan queue:work redis --sleep=3 --tries=3
autostart=true
autorestart=true
numprocs=4
redirect_stderr=true
stdout_logfile=/var/log/shipparts-worker.log
user=www-data
EOF

# Restart supervisor
sudo systemctl restart supervisor
```

#### 6. Setup Cron Jobs
```bash
# Edit crontab
sudo crontab -e

# Add Laravel scheduler
* * * * * cd /var/www/shipparts && php artisan schedule:run >> /dev/null 2>&1
```

#### 7. Redis Configuration
```bash
# Secure Redis
sudo tee /etc/redis/redis.conf > /dev/null <<EOF
bind 127.0.0.1 ::1
port 6379
requirepass redis_password_here
maxmemory 2gb
maxmemory-policy allkeys-lru
appendonly yes
appendfsync everysec
EOF

sudo systemctl restart redis-server
```

---

### Phase 3: Monitoring & Maintenance

#### 1. Application Monitoring
```bash
# Install monitoring tools
sudo apt install -y htop iotop nethogs

# Setup log rotation
sudo tee /etc/logrotate.d/shipparts > /dev/null <<EOF
/var/log/shipparts-*.log
/var/www/shipparts/storage/logs/*.log
{
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    sharedscripts
}
EOF
```

#### 2. Backup Strategy
```bash
#!/bin/bash
# Daily database backup
BACKUP_DIR="/backups/mysql"
DATE=$(date +%Y%m%d_%H%M%S)

mkdir -p $BACKUP_DIR

# MySQL backup
mysqldump -u root ship_spare_parts | gzip > $BACKUP_DIR/ship_spare_parts_$DATE.sql.gz

# Keep last 30 days
find $BACKUP_DIR -type f -mtime +30 -delete

# Upload to S3
aws s3 cp $BACKUP_DIR/ship_spare_parts_$DATE.sql.gz s3://your-bucket/backups/
```

#### 3. Automatic Updates
```bash
# Enable automatic security updates
sudo apt install -y unattended-upgrades
sudo dpkg-reconfigure -plow unattended-upgrades
```

---

### Phase 4: Performance Optimization

#### 1. CDN Configuration (Cloudflare)
```
- Enable minification (JS, CSS, HTML)
- Enable caching for assets
- Enable HTTP/2
- Setup page rules for caching
- Enable DDoS protection
```

#### 2. Image Optimization
```bash
# Install ImageMagick
sudo apt install -y imagemagick webp

# Optimize existing images
find /var/www/shipparts/storage/app/public -name "*.jpg" -exec convert {} -quality 80 {} \;
find /var/www/shipparts/storage/app/public -name "*.png" -exec convert {} -strip {} \;
```

#### 3. Database Optimization
```sql
-- Analyze tables
ANALYZE TABLE products;
ANALYZE TABLE orders;
ANALYZE TABLE order_items;

-- Check for fragmentation
SELECT object_name, object_type, data_free 
FROM information_schema.innodb_tablespaces_brief 
WHERE object_type = 'TABLE' AND data_free > 0;

-- Rebuild if needed
OPTIMIZE TABLE products;
```

---

### Phase 5: Security Hardening

#### 1. Firewall Setup
```bash
# UFW configuration
sudo ufw enable
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow 22/tcp      # SSH
sudo ufw allow 80/tcp      # HTTP
sudo ufw allow 443/tcp     # HTTPS
sudo ufw allow 3306/tcp    # MySQL (from app server only)
sudo ufw allow 6379/tcp    # Redis (from app server only)
```

#### 2. Fail2Ban Setup
```bash
sudo apt install -y fail2ban

# Create jail config
sudo tee /etc/fail2ban/jail.d/shipparts.conf > /dev/null <<EOF
[DEFAULT]
bantime = 3600
findtime = 600
maxretry = 5

[sshd]
enabled = true

[nginx-http-auth]
enabled = true

[nginx-limit-req]
enabled = true
port = http,https
filter = nginx-limit-req
logpath = /var/log/nginx/shipparts-error.log
findtime = 60
maxretry = 10
EOF

sudo systemctl restart fail2ban
```

#### 3. SSH Hardening
```bash
# Edit SSH config
sudo tee -a /etc/ssh/sshd_config > /dev/null <<EOF
PermitRootLogin no
PasswordAuthentication no
PubkeyAuthentication yes
X11Forwarding no
MaxAuthTries 3
MaxSessions 10
ClientAliveInterval 300
ClientAliveCountInterval 0
EOF

sudo systemctl restart ssh
```

#### 4. File Permissions
```bash
# Proper permission settings
chmod 755 /var/www/shipparts
chmod 644 /var/www/shipparts/public/*.php
chmod 755 /var/www/shipparts/public
chmod 755 /var/www/shipparts/storage
chmod 644 /var/www/shipparts/.env
chmod 700 /var/www/shipparts/.git
```

---

### Phase 6: Deployment Pipeline

#### 1. Zero-Downtime Deployment Script
```bash
#!/bin/bash
set -e

REPO="https://github.com/yourrepo/shipparts.git"
DEPLOY_USER="deploy"
DEPLOY_PATH="/var/www/shipparts"
BACKUP_PATH="/var/backups/shipparts"

# Create backup
sudo -u deploy mkdir -p $BACKUP_PATH
sudo -u deploy cp -r $DEPLOY_PATH $BACKUP_PATH/$(date +%Y%m%d_%H%M%S)

# Create new release
NEW_RELEASE=$DEPLOY_PATH/releases/$(date +%Y%m%d_%H%M%S)
sudo -u deploy mkdir -p $NEW_RELEASE

# Clone new code
sudo -u deploy git clone --depth 1 --branch main $REPO $NEW_RELEASE

# Install dependencies
cd $NEW_RELEASE
sudo -u deploy composer install --no-dev --optimize-autoloader
sudo -u deploy npm install && sudo -u deploy npm run build

# Symlink shared files
sudo -u deploy ln -s $DEPLOY_PATH/shared/.env $NEW_RELEASE/.env
sudo -u deploy ln -s $DEPLOY_PATH/shared/storage $NEW_RELEASE/storage

# Run migrations
php artisan migrate --force

# Cache config/routes/views
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Switch symlink (atomic operation)
cd $DEPLOY_PATH
sudo -u deploy ln -sfn $NEW_RELEASE/public current/public

# Reload PHP-FPM and Nginx
sudo systemctl reload php8.3-fpm
sudo systemctl reload nginx

# Queue restart
sudo systemctl restart supervisor

echo "Deployment completed successfully!"
```

---

## CONFIGURATION CHECKLIST

- [ ] Server OS installed (Ubuntu 22.04 LTS)
- [ ] All packages installed (PHP, Nginx, MySQL, Redis)
- [ ] Database created and user configured
- [ ] Application cloned and dependencies installed
- [ ] Environment file configured (.env)
- [ ] Database migrations run
- [ ] SSL certificate installed
- [ ] Nginx server block configured
- [ ] PHP-FPM configured
- [ ] Queue workers setup (Supervisor)
- [ ] Cron jobs configured
- [ ] Firewall configured
- [ ] Fail2Ban installed
- [ ] Monitoring setup
- [ ] Backup script scheduled
- [ ] CDN configured
- [ ] Email service configured
- [ ] Payment gateway API keys added
- [ ] Admin user created
- [ ] Initial data seeded (brands, categories, FAQs)

## MAINTENANCE COMMANDS

```bash
# View logs
tail -f /var/log/nginx/shipparts-error.log
tail -f /var/log/php-fpm-shipparts-slow.log

# Database optimization
php artisan tinker
DB::statement('OPTIMIZE TABLE products');
DB::statement('ANALYZE TABLE orders');

# Clear caches
php artisan cache:clear
php artisan view:clear
php artisan config:cache

# Queue monitoring
php artisan queue:work redis --verbose

# Generate sample data
php artisan db:seed --class=SampleDataSeeder
```

This completes the production-ready deployment guide.
