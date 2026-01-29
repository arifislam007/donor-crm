# Docker Deployment Guide
## NGO Donor Management System

This document provides instructions for deploying the NGO Donor Management System using Docker and Docker Compose.

---

## Prerequisites

- Docker Engine 20.10+ 
- Docker Compose 2.0+
- 2GB RAM minimum
- 10GB disk space

---

## Quick Start

### 1. Clone and Navigate

```bash
cd ngo-donor-system
```

### 2. Configure Environment

Copy the example environment file and customize:

```bash
cp .env.example .env
```

Edit `.env` with your configuration:

```env
APP_NAME="NGO Donor System"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost:8080

# Database
DB_HOST=db
DB_PORT=3306
DB_DATABASE=ngo_donor_system
DB_USERNAME=ngo_user
DB_PASSWORD=your_secure_password

# Email (SendGrid)
MAIL_MAILER=sendgrid
MAIL_FROM_ADDRESS=noreply@yourdomain.org
MAIL_FROM_NAME="NGO Donor System"
SENDGRID_API_KEY=your_sendgrid_api_key
```

### 3. Start Containers

```bash
# Build and start all services
docker-compose up -d --build

# View logs
docker-compose logs -f

# Check status
docker-compose ps
```

### 4. Access the Application

| Service | URL | Description |
|---------|-----|-------------|
| Application | http://localhost:8080 | Main application |
| phpMyAdmin | http://localhost:8081 | Database management |

---

## Services Overview

| Service | Image | Port | Description |
|---------|-------|------|-------------|
| app | Custom PHP 8.2-FPM | 9000 (internal) | PHP application |
| web | Nginx Alpine | 8080 | Web server |
| db | MySQL 8.0 | 3306 (internal) | Database |
| phpmyadmin | phpMyAdmin | 8081 | Database UI |

---

## Management Commands

### Start Services
```bash
docker-compose up -d
```

### Stop Services
```bash
docker-compose down
```

### Stop and Remove Volumes
```bash
docker-compose down -v
```

### Rebuild After Changes
```bash
docker-compose up -d --build
```

### View Logs
```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f app
docker-compose logs -f web
docker-compose logs -f db
```

### Execute Commands in Container
```bash
# Enter PHP container
docker-compose exec app sh

# Run database setup
docker-compose exec app php setup.php

# Run Composer
docker-compose exec app composer install
```

---

## Database Access

### Command Line
```bash
docker-compose exec db mysql -u ngo_user -p ngo_donor_system
```

### phpMyAdmin
- URL: http://localhost:8081
- Server: db
- Username: root
- Password: root_password

---

## Production Deployment

### 1. Update Environment for Production

```env
APP_ENV=production
APP_DEBUG=false
```

### 2. Use Strong Passwords

Update `.env` with strong passwords:

```env
DB_PASSWORD=your_very_strong_password_here
```

### 3. Configure HTTPS (Recommended)

For production, configure SSL/TLS using a reverse proxy like Traefik or Caddy:

```yaml
# docker-compose.prod.yml
services:
  traefik:
    image: traefik:v2.9
    command:
      - "--providers.docker=true"
      - "--providers.docker.exposedbydefault=false"
      - "--entrypoints.web.address=:80"
      - "--entrypoints.websecure.address=:443"
      - "--certificatesresolvers.letsencrypt.acme.email=your@email.com"
      - "--certificatesresolvers.letsencrypt.acme.storage=/letsencrypt/acme.json"
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - /var/run/docker.sock:/var/run/docker.sock
      - ./letsencrypt:/letsencrypt
    depends_on:
      - web
```

### 4. Backup Strategy

Create a backup script:

```bash
#!/bin/bash
# backup.sh
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="./backups"
mkdir -p $BACKUP_DIR

docker-compose exec db mysqldump -u root -proot_password ngo_donor_system > $BACKUP_DIR/backup_$DATE.sql
echo "Backup created: $BACKUP_DIR/backup_$DATE.sql"
```

Add to crontab:
```bash
0 2 * * * /path/to/backup.sh
```

---

## Troubleshooting

### Container Won't Start

```bash
# Check logs
docker-compose logs app
docker-compose logs web
docker-compose logs db
```

### Database Connection Failed

```bash
# Verify MySQL is running
docker-compose ps db

# Check MySQL logs
docker-compose logs db

# Test connection
docker-compose exec db mysqladmin ping -h localhost -u root -proot_password
```

### Permission Issues

```bash
# Fix permissions
docker-compose exec app chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
```

### Clear All Data and Start Fresh

```bash
docker-compose down -v
docker-compose up -d
```

---

## Environment Variables

| Variable | Default | Description |
|----------|---------|-------------|
| APP_ENV | production | Environment (production/development) |
| APP_DEBUG | false | Enable debug mode |
| APP_URL | http://localhost | Application URL |
| DB_HOST | db | Database host |
| DB_PORT | 3306 | Database port |
| DB_DATABASE | ngo_donor_system | Database name |
| DB_USERNAME | ngo_user | Database user |
| DB_PASSWORD | - | Database password |
| MAIL_MAILER | sendgrid | Email driver |
| SENDGRID_API_KEY | - | SendGrid API key |

---

## Scaling

For high availability:

```yaml
# docker-compose.scale.yml
services:
  app:
    deploy:
      replicas: 3
    depends_on:
      - db
  
  web:
    deploy:
      replicas: 2
    depends_on:
      - app
```

---

## Security Recommendations

1. **Use strong passwords** for database and admin accounts
2. **Enable HTTPS** in production
3. **Keep Docker updated** regularly
4. **Limit container permissions**
5. **Use secrets management** for sensitive data
6. **Enable firewall** to restrict port access
7. **Regular backups** of database

---

## Support

For issues or questions, refer to:
- Application logs: `docker-compose logs`
- Nginx logs: `docker-compose exec web cat /var/log/nginx/access.log`
- PHP errors: Check application logs
