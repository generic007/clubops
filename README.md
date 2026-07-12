# 🃏 ClubOps OS — Private Poker Club Operations System

> Operations, CRM, ledger, reconciliation, promotion, and reporting for a private poker club.

**Status:** 🔴 Pre-Deployment (blocked on DreamHost SSH) | **Stack:** Laravel 11, MySQL 8, Blade + Bootstrap 5, Alpine.js | **Deployment:** DreamHost Shared Hosting

---

## Screenshots

*Screenshots coming soon — deploy first, then capture.*

---

## What It Is

ClubOps OS is a complete operations platform for a private poker club. It handles CRM, financial ledger, game session management, promotions, reconciliation, player intelligence, and compliance — all in one application.

**What it is NOT:** A poker bot, cashier, payment processor, or platform automation tool. This system is for back-office operations and recordkeeping only.

---

## Features

### 👤 Player CRM
- **Player profiles** — contact info, join date, status, notes
- **Platform account linking** — connect external platform accounts per player
- **Tags** — categorize players (regular, VIP, new, flagged)
- **Notes** — staff observations, player preferences, seat preferences
- **Dormant player detection** — `artisan clubops:check-dormant-players` flags inactive 30+ days
- **Player export** — `artisan clubops:export-players` CSV export

### 📊 Ledger & Financials
- **Ledger accounts** — per-player balance tracking
- **Ledger entries** — immutable financial records (DECIMAL(18,2) only, never floats)
- **Ledger lines** — individual line items with full audit trail
- **Entry immutability** — once written, entries cannot be deleted. Corrections require reversal entries
- **Every entry has:** actor, timestamp, reason
- **Daily close** — locks a day from further edits via `artisan clubops:daily-close`
- **Balance recalculation** — `artisan clubops:recalculate-balances` rebuilds all account balances
- **Ledger audit** — `artisan clubops:audit-ledger` verifies ledger integrity

### ♠️ Game & Session Management
- **Games CRUD** — define game types (Hold'em, Omaha, mixed games)
- **Sessions** — record game sessions with date, time, game type, participants
- **Session attendance** — link players to specific sessions

### 📐 Reconciliation
- **Reconciliations** — end-of-day/week balance checks
- **Reconciliation items** — line-by-line matching of expected vs. actual
- **Discrepancy tracking** — identify and investigate variances

### 🏆 Promotions & Redemptions
- **Promotions** — create time-limited or permanent promos (high-hand bonuses, bad-beat jackpots, etc.)
- **Redemptions** — track when players claim promotional rewards
- **Eligibility** — link promotions to player tags or game types

### 🚩 Risk & Compliance
- **Risk flags** — flag players for unusual activity or behavior patterns
- **Compliance profiles** — per-player compliance settings
- **Exclusions** — self-exclusion and cool-off period management
- **Responsible gaming statuses** — cool-off, self-exclusion with expiry dates
- **Audit log** — full trail of all compliance actions

### 🎫 Support Tickets
- **Ticket management** — create, assign, resolve support tickets
- **Comments** — threaded discussion per ticket
- **Attachments** — upload screenshots, documents, evidence
- **Status tracking** — open, in-progress, resolved, closed

### 📨 Communication
- **Templates** — reusable message templates for common communications
- **Message drafts** — save and edit messages before sending

### 📤 Import System
- **Import framework** — upload CSV/XLSX data files
- **Preview before import** — review mapped columns
- **Import rows** — row-level tracking with status and errors
- **Rollback support** — undo an import if something goes wrong

### 📱 Mobile PWA
- Fully responsive — works on iPhone and Android
- Installable as PWA: Share → Add to Home Screen
- Camera upload for screenshots and evidence
- Touch-friendly forms and navigation

### 🔐 Authentication & Roles
- **Agent-based auth** — secure session-based login
- **Owner role** — full system access
- **Manager role** — operational access, restricted financial controls
- **Role-based middleware** — fine-grained access control on routes

---

## Quick Start

### Prerequisites
- DreamHost shared hosting (or any PHP 8.2+ server)
- MySQL 8+
- SSH access (**currently blocked — deployment pending**)

### 1. Create Database
- DreamHost Panel → Goodies → MySQL Databases
- Create database: `clubops_db`
- Create user: `clubops_user`
- Note the hostname (e.g., `mysql.clubops.example.com`)

### 2. Deploy (when SSH is available)
```bash
ssh user@dreamhost.com
cd ~/clubops.example.com
rsync -avz --exclude node_modules --exclude .git --exclude .env \
  ./ user@dreamhost.com:~/clubops.example.com/
composer install --no-dev --optimize-autoloader
cp .env.example .env
# Edit .env with database credentials:
# APP_ENV=production
# APP_DEBUG=false
# APP_URL=https://clubops.example.com
# DB_CONNECTION=mysql
# DB_HOST=mysql.clubops.example.com
# DB_DATABASE=clubops_db
# DB_USERNAME=clubops_user
# DB_PASSWORD=your_password
php artisan key:generate
php artisan migrate --force
php artisan db:seed --class=DatabaseSeeder
```

Set document root to `~/clubops.example.com/public` in DreamHost panel.

### 3. Login
| Role    | Email                      | Password   |
|---------|----------------------------|------------|
| Owner   | `owner@clubops.local`      | `change-me` |
| Manager | `manager@clubops.local`    | `change-me` |

**Change passwords immediately on first login.**

### 4. Configure Cron
DreamHost Panel → Advanced → Cron Jobs → Add:
```
Command: cd /home/USER/clubops.example.com && /usr/bin/php8.2 artisan schedule:run >> /dev/null 2>&1
Frequency: Every 5 minutes
```

---

## Architecture

### Database (25 tables)
| Group | Tables |
|-------|--------|
| **Auth** | Agents |
| **CRM** | Players, Platform Accounts, Tags, Notes |
| **Ledger** | Ledger Accounts, Ledger Entries, Ledger Lines |
| **Operations** | Games, Sessions |
| **Financial** | Reconciliations, Reconciliation Items |
| **Promotions** | Promotions, Redemptions |
| **Support** | Support Tickets, Comments, Attachments |
| **Data** | Imports, Import Rows |
| **Compliance** | Risk Flags, Communication Templates, Message Drafts, Compliance Profiles, Exclusions |
| **Audit** | Audit Logs |

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

## Artisan Commands

```bash
php artisan clubops:daily-close           # Lock today's ledger
php artisan clubops:recalculate-balances  # Rebuild all account balances
php artisan clubops:audit-ledger          # Verify ledger integrity
php artisan clubops:export-players        # Export player list as CSV
php artisan clubops:check-dormant-players # Find players inactive 30+ days
php artisan db:seed --class=DatabaseSeeder # Create admin accounts
php artisan db:seed --class=DemoSeeder    # Seed demo data for testing
```

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

## Troubleshooting

| Issue | Solution |
|-------|----------|
| White screen after deploy | Check `storage/logs/laravel.log`. Run `php artisan optimize:clear` |
| 500 error on login | Run `php artisan migrate --force`. Check `.env` DB credentials |
| Missing CSS/JS | Assets use CDN. Check internet connectivity |
| SwiftMailer errors | Configure MAIL_* in `.env` or set `MAIL_DRIVER=log` |

---

## License

Proprietary. For use by authorized club operators only.
