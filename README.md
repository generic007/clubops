# ClubOps OS — Private Poker Club Operations System

**Date:** 2026-07-01  
**Stack:** Laravel 11, MySQL, Blade + Bootstrap 5, Alpine.js  
**Deployment:** DreamHost Shared Hosting  
**What it is:** Operations, CRM, ledger, reconciliation, promotion, and reporting for a private poker club.  
**What it is NOT:** Poker bot, cashier, payment processor, platform automation tool.

---

## Quick Start

### 1. Create DreamHost MySQL Database
- DreamHost Panel → Goodies → MySQL Databases
- Create database: `clubops_db`
- Create user: `clubops_user`
- Note the hostname (e.g., `mysql.clubops.example.com`)

### 2. SSH into DreamHost
```bash
ssh user@example.dreamhost.com
```

### 3. Check PHP Version
```bash
/usr/bin/php8.2 -v
ls /usr/bin/php*
```
Minimum: **PHP 8.2+**

### 4. Install Composer (if not present)
```bash
cd ~
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=~/bin
php -r "unlink('composer-setup.php');"
export PATH="$HOME/bin:$PATH"
```

### 5. Deploy Files
```bash
# Option A: Git clone
cd ~/clubops.example.com
git clone https://github.com/generic007/clubops.git .

# Option B: rsync from local
rsync -avz --exclude node_modules --exclude .git --exclude .env \
  ./ user@example.dreamhost.com:~/clubops.example.com/
```

### 6. Install Dependencies
```bash
cd ~/clubops.example.com
composer install --no-dev --optimize-autoloader
```

### 7. Configure Environment
```bash
cp .env.example .env
# Edit .env with your DreamHost MySQL credentials:
# APP_ENV=production
# APP_DEBUG=false
# APP_URL=https://clubops.example.com
# DB_CONNECTION=mysql
# DB_HOST=mysql.clubops.example.com
# DB_DATABASE=clubops_db
# DB_USERNAME=clubops_user
# DB_PASSWORD=your_password
```

### 8. Generate App Key
```bash
php artisan key:generate
```

### 9. Run Migrations
```bash
php artisan migrate --force
```

### 10. Seed Admin Account
```bash
php artisan db:seed --class=DatabaseSeeder
```
Default accounts:
- `owner@clubops.local` / `change-me` (Owner role)
- `manager@clubops.local` / `change-me` (Manager role)

**Change passwords immediately on first login.**

### 11. Set Permissions
```bash
chmod -R 775 storage bootstrap/cache
```

### 12. Point Domain to Laravel
- DreamHost Panel → Manage Domains
- Set document root to: `/home/USER/clubops.example.com/public`

### 13. Configure Cron
DreamHost Panel → Advanced → Cron Jobs → Add:
```
Command: cd /home/USER/clubops.example.com && /usr/bin/php8.2 artisan schedule:run >> /dev/null 2>&1
Frequency: Every 5 minutes
```

### 14. Verify
Visit `https://clubops.example.com` and log in with the owner account.

---

## System Architecture

### Three Surfaces
1. **Desktop Web App** — Full admin dashboard (Laravel Blade + Bootstrap 5)
2. **Mobile PWA** — Installable on iPhone/Android home screen (same codebase, responsive)
3. **Future Native App** — Capacitor wrapper or Flutter (uses Laravel API, post-MVP)

### Database (26 tables)
Agents, Players, Platform Accounts, Tags, Notes, Ledger Accounts, Ledger Entries, Ledger Lines, Reconciliations, Reconciliation Items, Promotions, Redemptions, Games, Sessions, Support Tickets, Comments, Attachments, Imports, Import Rows, Risk Flags, Communication Templates, Message Drafts, Compliance Profiles, Exclusions, Audit Logs

### Ledger Rules (Non-Negotiable)
- **DECIMAL(18,2) only.** Never FLOAT/DOUBLE.
- **Entries are immutable.** Corrections require reversal entries.
- **Every entry has actor, timestamp, reason.**
- **No destructive deletes.**
- **Daily close locks a day from further edits.**

### Compliance Rules
- No payment processing
- No scraping or botting
- No platform term evasion
- Label ledger as operational recordkeeping
- Responsible gaming statuses (cool-off, self-exclusion)

---

## Backup Strategy

### Database Backup
```bash
#!/bin/bash
BACKUP_DIR=~/backups/clubops
mkdir -p $BACKUP_DIR
DATE=$(date +%Y%m%d)
mysqldump -u clubops_user -p clubops_db > $BACKUP_DIR/clubops-$DATE.sql
tar -czf $BACKUP_DIR/clubops-uploads-$DATE.tar.gz -C ~/clubops.example.com storage/app/private
find $BACKUP_DIR -name "*.sql" -mtime +30 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +30 -delete
```

### Safe Update Process
```bash
# 1. Backup database + uploads
# 2. Put app in maintenance mode
php artisan down --retry=60

# 3. Pull latest code
git pull origin main

# 4. Update dependencies
composer install --no-dev --optimize-autoloader

# 5. Run new migrations
php artisan migrate --force

# 6. Clear caches
php artisan optimize:clear

# 7. Bring app up
php artisan up

# 8. Verify
```

---

## Artisan Commands

```bash
php artisan clubops:daily-close           # Lock today's ledger
php artisan clubops:recalculate-balances  # Rebuild all account balances
php artisan clubops:audit-ledger           # Verify ledger integrity
php artisan clubops:export-players         # Export player list as CSV
php artisan clubops:check-dormant-players  # Find players inactive 30+ days
php artisan db:seed --class=DemoSeeder     # Seed demo data for testing
```

---

## Mobile PWA

ClubOps is a Progressive Web App:
1. Visit `https://clubops.example.com` on iPhone/Android
2. Tap Share → Add to Home Screen
3. Use like a native app
4. Camera upload for screenshots/evidence
5. Touch-friendly forms and navigation

---

## Troubleshooting

| Issue | Solution |
|-------|----------|
| White screen after deploy | Check `storage/logs/laravel.log`. Run `php artisan optimize:clear` |
| 500 error on login | Run `php artisan migrate --force`. Check `.env` DB credentials |
| Uploads not working | Check `storage/app/private/` permissions (`chmod -R 775`) |
| Cron not running | Verify in DreamHost panel. Check `storage/logs/laravel.log` |
| Missing CSS/JS | Assets use CDN. Check internet connectivity |
| SwiftMailer errors | Configure MAIL_* in `.env` or set `MAIL_DRIVER=log` |

---

## License

Proprietary. For use by authorized club operators only.
