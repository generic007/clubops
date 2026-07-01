# ClubOps OS — Build Plan v2

**Date:** 2026-07-01  
**Deployment Target:** DreamHost Shared Hosting  
**Stack:** Laravel 11, MySQL, Blade + Bootstrap 5, Alpine.js (CDN)  
**Architecture Principle:** Boring, durable, cheap, auditable.  
**What it is:** Operations, CRM, ledger, reconciliation, promotion, and reporting system for a private poker club.  
**What it is NOT:** Poker bot, cashier, gambling-payment processor, platform automation tool, scraper, or evader of terms.

---

## 1. Three-Surface Strategy

The product supports exactly three surfaces, built in order:

### Surface 1: Desktop Web App (MVP)
- Full admin dashboard
- Best for owner/accounting/reconciliation/reporting
- Laravel Blade + Bootstrap 5, server-rendered
- DreamHost hosted

### Surface 2: Mobile PWA (Phase 1)
- Primary mobile experience for owner, agents, support staff
- Installable on iPhone/Android home screen
- Must feel app-like, hosted as Laravel web app
- Camera/photo upload for receipts and evidence
- Touch-friendly, bottom nav, quick actions
- This is the first "mobile app version" — easiest to host on DreamHost

### Surface 3: Future Native App (Optional, Post-MVP)
- Capacitor wrapper around PWA, or Flutter/React Native
- Uses same Laravel backend via versioned JSON API
- No business logic duplicated in the app
- Only build if the business actually needs App Store distribution

---

## 2. Architecture Overview

### Stack Decision

| Layer | Choice | Why |
|-------|--------|-----|
| Framework | Laravel 11 | Standard PHP, DreamHost-native, huge ecosystem |
| Database | MySQL (DreamHost) | First-class DreamHost support |
| Frontend | Blade + Bootstrap 5 + Alpine.js via CDN | No Node build step in production |
| Auth | Laravel Breeze (Blade stack) | Built-in, DreamHost-compatible |
| Jobs | Laravel Queue + database driver via cron | No Redis/Supervisor needed |
| Scheduler | `php artisan schedule:run` via DreamHost cron | Standard approach |
| File Storage | `storage/app/private/` | Outside public web root |
| Emails | Laravel Mail + SMTP (DreamHost or SendGrid) | Standard |
| Assets | CDN for Bootstrap/Alpine; CSS/JS compiled locally then committed | Zero build deps on server |
| PWA | manifest.json + service worker | Installable, mobile-first |

### Directory Structure

```
clubops/
├── app/
│   ├── Enums/                 # Backed enums (status, types, roles)
│   ├── Http/
│   │   ├── Controllers/       # Resource controllers + API controllers
│   │   ├── Requests/          # Form requests for validation
│   │   ├── Resources/         # API resources (future native app)
│   │   └── Middleware/         # Role-based middleware
│   ├── Models/                # Eloquent models
│   ├── Policies/              # Authorization policies
│   └── Services/              # Business logic layer
├── bootstrap/
├── config/
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   ├── build/                 # Local-built assets
│   ├── icons/                 # PWA icons
│   ├── manifest.json
│   ├── sw.js
│   └── offline.html
├── resources/
│   └── views/
│       ├── components/        # Blade components
│       ├── layouts/           # Admin layout + mobile layout
│       ├── livewire/          # Livewire components (if needed)
│       └── ...
├── routes/
│   ├── web.php
│   └── api.php               # Future native API
├── storage/
│   ├── app/private/           # Uploaded docs, screenshots, evidence
│   └── app/public/
├── artisan
├── composer.json
└── .env
```

---

## 3. Database Schema

### MySQL Design Rules
- **BIGINT unsigned auto-increment** primary keys (no UUIDs unless a strong reason)
- **DECIMAL(18,2) or DECIMAL(20,4)** for all ledger amounts. Never FLOAT/DOUBLE.
- **DATETIME** consistently; use TIMESTAMP where timezone-aware
- **Indexes** on: player_id, agent_id, platform, status, created_at, ledger_account_id, reconciliation_id, audit actor/date
- **Soft deletes** for CRM records (players, agents, tags, notes)
- **No soft deletes** on ledger records — immutability via reversal entries
- **Files stored outside public web root** — served only through authenticated controller routes

### Complete Table List

```
users                    # Laravel auth users
roles                    # Role definitions
role_user                # Pivot: user ↔ role
agents                   # Extended agent/admin profiles
players                  # Player CRM
player_platform_accounts # ClubGG, PPPoker, PokerBros usernames
player_notes             # Chronological notes (auditable)
tags                     # Tag definitions
player_tag               # Pivot: player ↔ tag
leads                    # Lead intake records
onboarding_steps         # Per-player onboarding checklist items

ledger_accounts          # Chart of accounts
ledger_entries           # Immutable journal entries
ledger_lines             # Individual debit/credit lines

reconciliations          # Daily reconciliation sessions
reconciliation_items     # Matched/unmatched items

promotions               # Promo definitions
promotion_rules          # Eligibility rules
promotion_redemptions    # Claims/redemptions

games                    # Game definitions (type, stakes, etc.)
game_sessions            # Per-player game session records

support_tickets          # Support/dispute tickets
ticket_comments          # Ticket conversation

attachments              # Polymorphic file attachments
imports                  # CSV/bulk import sessions
import_rows              # Individual import row status

audit_logs               # Sensitive action audit trail
risk_flags               # Player risk/trust markers

communication_templates  # Message templates
message_drafts           # Saved copy/paste drafts

compliance_profiles      # Per-player compliance data
exclusions               # Cool-off/self-exclusion records
```

### Enums (PHP Backed Enums)

```php
enum PlayerStatus: string {
    case Lead = 'lead';
    case Pending = 'pending';
    case Active = 'active';
    case Inactive = 'inactive';
    case VIP = 'vip';
    case Suspended = 'suspended';
    case Banned = 'banned';
    case Excluded = 'excluded';       // Self-exclusion / cool-off
}

enum AgentRole: string {
    case Owner = 'owner';
    case Manager = 'manager';
    case Agent = 'agent';
    case Support = 'support';
    case Accountant = 'accountant';
    case Auditor = 'auditor';
}

enum TransactionType: string {
    case PlatformAdjustment = 'platform_adjustment';
    case PromoCredit = 'promo_credit';
    case PromoDebit = 'promo_debit';
    case AgentTransfer = 'agent_transfer';
    case Correction = 'correction';
    case DisputeHold = 'dispute_hold';
    case Reconciliation = 'reconciliation';
    case Void = 'void';
    case ManualEntry = 'manual_entry';
}

enum TicketType: string {
    case LedgerQuestion = 'ledger_question';
    case PromoIssue = 'promo_issue';
    case PlatformAccess = 'platform_access';
    case GameComplaint = 'game_complaint';
    case Collusion = 'collusion';
    case Behavior = 'behavior';
    case Disconnection = 'disconnection';
    case Other = 'other';
}

enum TicketStatus: string {
    case Open = 'open';
    case InProgress = 'in_progress';
    case Resolved = 'resolved';
    case Closed = 'closed';
}

enum PromoType: string {
    case WelcomeBonus = 'welcome_bonus';
    case ReferralBonus = 'referral_bonus';
    case Reactivation = 'reactivation';
    case VIPOffer = 'vip_offer';
    case Tournament = 'tournament';
    case StartingIncentive = 'starting_incentive';
    case VolumeReward = 'volume_reward';
}

enum RiskLevel: string {
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
    case Critical = 'critical';
}
```

### Core Migrations

```php
// 001_create_agents_table
Schema::create('agents', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('role');                    // AgentRole enum
    $table->string('phone')->nullable();
    $table->boolean('active')->default(true);
    $table->foreignId('created_by')->nullable()->constrained('agents');
    $table->timestamps();
    $table->softDeletes();
});

// 002_create_players_table
Schema::create('players', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('preferred_name')->nullable();
    $table->string('phone')->nullable();
    $table->string('email')->nullable();
    $table->string('status')->default('lead');   // PlayerStatus
    $table->string('referral_source')->nullable();
    $table->foreignId('agent_id')->nullable()->constrained('agents');
    $table->foreignId('assigned_admin_id')->nullable()->constrained('agents');
    $table->string('risk_status')->nullable();    // RiskLevel
    $table->timestamp('last_contacted_at')->nullable();
    $table->timestamp('last_played_at')->nullable();
    $table->boolean('compliance_complete')->default(false);
    $table->text('notes')->nullable();
    $table->softDeletes();
    $table->timestamps();

    $table->index('status');
    $table->index('agent_id');
    $table->index('risk_status');
});

// 003_create_player_platform_accounts
Schema::create('player_platform_accounts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('player_id')->constrained()->onDelete('cascade');
    $table->string('platform');                 // ClubGG, PPPoker, PokerBros
    $table->string('username');
    $table->string('user_id')->nullable();
    $table->boolean('verified')->default(false);
    $table->timestamps();
    $table->unique(['platform', 'username']);
});

// 004 Create tags + player_tag pivot
Schema::create('tags', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique();
    $table->string('color')->nullable();
    $table->timestamps();
});

Schema::create('player_tag', function (Blueprint $table) {
    $table->foreignId('player_id')->constrained()->onDelete('cascade');
    $table->foreignId('tag_id')->constrained()->onDelete('cascade');
    $table->primary(['player_id', 'tag_id']);
});

// 005_create_player_notes
Schema::create('player_notes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('player_id')->constrained()->onDelete('cascade');
    $table->foreignId('agent_id')->constrained();
    $table->text('note');
    $table->string('category')->nullable();     // general, compliance, warning, note_to_self
    $table->timestamps();
});

// 006_create_ledger_accounts
Schema::create('ledger_accounts', function (Blueprint $table) {
    $table->id();
    $table->string('code', 20)->unique();       // Account code: 1000, 2000, etc.
    $table->string('name');
    $table->string('type');                     // asset, liability, equity, income, expense
    $table->string('currency')->default('USD');
    $table->decimal('balance', 18, 2)->default(0);
    $table->boolean('active')->default(true);
    $table->text('description')->nullable();
    $table->timestamps();
});

// 007_create_ledger_entries — IMMUTABLE
Schema::create('ledger_entries', function (Blueprint $table) {
    $table->id();
    $table->string('entry_number')->unique();   // YYYYMMDD-XXXXX
    $table->string('type');                     // TransactionType
    $table->text('description');
    $table->foreignId('created_by')->constrained('agents');
    $table->morphs('source');                   // Polymorphic: Player, Agent, etc.
    $table->string('reference')->nullable();    // External ref / attachment filename
    $table->date('entry_date');
    $table->foreignId('reversed_entry_id')->nullable()->constrained('ledger_entries');
    $table->boolean('locked')->default(false);
    $table->timestamps();

    $table->index('entry_date');
    $table->index('type');
});

// 008_create_ledger_lines
Schema::create('ledger_lines', function (Blueprint $table) {
    $table->id();
    $table->foreignId('entry_id')->constrained('ledger_entries');
    $table->foreignId('account_id')->constrained('ledger_accounts');
    $table->foreignId('player_id')->nullable()->constrained();
    $table->decimal('debit', 18, 2)->default(0);
    $table->decimal('credit', 18, 2)->default(0);
    $table->timestamps();
    // Balance constraint enforced at service layer
});

// 009_create_reconciliations
Schema::create('reconciliations', function (Blueprint $table) {
    $table->id();
    $table->date('reconciliation_date');
    $table->string('status')->default('draft'); // draft, in_progress, complete, verified
    $table->decimal('platform_total', 18, 2);
    $table->decimal('ledger_total', 18, 2);
    $table->decimal('variance', 18, 2);
    $table->foreignId('created_by')->constrained('agents');
    $table->foreignId('locked_by')->nullable()->constrained('agents');
    $table->timestamp('locked_at')->nullable();
    $table->text('notes')->nullable();
    $table->timestamps();

    $table->unique('reconciliation_date');
});

// 010_create_reconciliation_items
Schema::create('reconciliation_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('reconciliation_id')->constrained()->onDelete('cascade');
    $table->foreignId('entry_id')->nullable()->constrained('ledger_entries');
    $table->decimal('amount', 18, 2);
    $table->string('type');                     // matched, unmatched_ledger, unmatched_platform
    $table->text('notes')->nullable();
    $table->timestamps();
});

// 011_create_support_tickets
Schema::create('support_tickets', function (Blueprint $table) {
    $table->id();
    $table->string('ticket_number')->unique();  // TKT-XXXXX
    $table->foreignId('player_id')->nullable()->constrained();
    $table->foreignId('assigned_to')->nullable()->constrained('agents');
    $table->string('subject');
    $table->text('description');
    $table->string('type');                     // TicketType
    $table->string('priority')->default('normal');
    $table->string('status')->default('open');
    $table->timestamp('resolved_at')->nullable();
    $table->timestamps();

    $table->index('status');
    $table->index('assigned_to');
});

// 012_create_ticket_comments
Schema::create('ticket_comments', function (Blueprint $table) {
    $table->id();
    $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
    $table->morphs('author');                   // Agent or Player
    $table->text('body');
    $table->timestamps();
});

// 013_create_promotions
Schema::create('promotions', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('type');                     // PromoType
    $table->text('description')->nullable();
    $table->decimal('value', 18, 2);
    $table->decimal('cap', 18, 2)->nullable();
    $table->decimal('total_liability', 18, 2)->default(0);
    $table->decimal('claimed_liability', 18, 2)->default(0);
    $table->timestamp('starts_at');
    $table->timestamp('ends_at')->nullable();
    $table->boolean('active')->default(true);
    $table->text('terms')->nullable();
    $table->json('eligibility_rules')->nullable(); // JSON for flexible rule engine
    $table->timestamps();
});

// 014_create_promotion_redemptions
Schema::create('promotion_redemptions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('promotion_id')->constrained();
    $table->foreignId('player_id')->constrained();
    $table->foreignId('ledger_entry_id')->nullable()->constrained('ledger_entries');
    $table->decimal('amount', 18, 2);
    $table->string('status')->default('pending');
    $table->text('notes')->nullable();
    $table->timestamp('claimed_at');
    $table->timestamps();
});

// 015_create_games
Schema::create('games', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('type');                     // PLO, NLH, mixed, etc.
    $table->string('stakes');                   // 5/5/10, 2/5, etc.
    $table->string('platform');                 // ClubGG, PPPoker, PokerBros
    $table->string('status')->default('scheduled'); // scheduled, running, completed, cancelled
    $table->timestamp('scheduled_at');
    $table->timestamp('started_at')->nullable();
    $table->timestamp('ended_at')->nullable();
    $table->text('notes')->nullable();
    $table->foreignId('created_by')->constrained('agents');
    $table->timestamps();
});

// 016_create_game_sessions
Schema::create('game_sessions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('game_id')->constrained()->onDelete('cascade');
    $table->foreignId('player_id')->constrained();
    $table->decimal('buy_in', 18, 2)->nullable();
    $table->decimal('cash_out', 18, 2)->nullable();
    $table->decimal('profit_loss', 18, 2)->nullable();
    $table->integer('duration_minutes')->nullable();
    $table->text('notes')->nullable();
    $table->timestamps();
});

// 017_create_attachments (polymorphic)
Schema::create('attachments', function (Blueprint $table) {
    $table->id();
    $table->morphs('attachable');               // Player, Ticket, LedgerEntry, Reconciliation, Import
    $table->foreignId('uploaded_by')->constrained('agents');
    $table->string('filename');
    $table->string('original_filename');
    $table->string('mime_type');
    $table->integer('size_bytes');
    $table->string('disk');                     // 'private'
    $table->string('path');
    $table->text('notes')->nullable();
    $table->timestamps();
});

// 018_create_imports
Schema::create('imports', function (Blueprint $table) {
    $table->id();
    $table->string('type');                     // players, ledger, reconciliation
    $table->string('filename');
    $table->string('status')->default('pending'); // pending, mapping, importing, complete, failed
    $table->integer('total_rows')->default(0);
    $table->integer('accepted_rows')->default(0);
    $table->integer('skipped_rows')->default(0);
    $table->integer('flagged_rows')->default(0);
    $table->text('error_log')->nullable();
    $table->foreignId('created_by')->constrained('agents');
    $table->timestamps();
});

// 019_create_import_rows
Schema::create('import_rows', function (Blueprint $table) {
    $table->id();
    $table->foreignId('import_id')->constrained()->onDelete('cascade');
    $table->integer('row_number');
    $table->json('raw_data');
    $table->json('mapped_data')->nullable();
    $table->string('status')->default('pending'); // pending, accepted, skipped, flagged
    $table->text('notes')->nullable();
    $table->timestamps();
});

// 020_create_audit_logs
Schema::create('audit_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('agent_id')->nullable()->constrained();
    $table->string('action');
    $table->string('auditable_type');
    $table->unsignedBigInteger('auditable_id');
    $table->json('old_values')->nullable();
    $table->json('new_values')->nullable();
    $table->string('ip_address', 45)->nullable();
    $table->text('description')->nullable();
    $table->timestamps();

    $table->index(['auditable_type', 'auditable_id']);
    $table->index('action');
    $table->index('created_at');
});

// 021_create_risk_flags
Schema::create('risk_flags', function (Blueprint $table) {
    $table->id();
    $table->foreignId('player_id')->constrained()->onDelete('cascade');
    $table->foreignId('raised_by')->constrained('agents');
    $table->foreignId('resolved_by')->nullable()->constrained('agents');
    $table->string('type');                     // collusion, tilt, credit, behavior, abuse
    $table->text('description');
    $table->string('severity')->default('medium');
    $table->string('status')->default('open');  // open, investigating, resolved, dismissed
    $table->timestamp('resolved_at')->nullable();
    $table->timestamps();
});

// 022_create_communication_templates
Schema::create('communication_templates', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('category');                // welcome, reminder, promo, support, concierge
    $table->string('channel');                 // sms, telegram, discord, whatsapp, email
    $table->text('body');
    $table->text('merge_fields')->nullable();  // {player_name}, {game_time}, etc.
    $table->string('tone')->default('professional'); // friendly, professional, short, vip, fun
    $table->boolean('active')->default(true);
    $table->timestamps();
});

// 023_create_message_drafts
Schema::create('message_drafts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('agent_id')->constrained();
    $table->foreignId('player_id')->nullable()->constrained();
    $table->text('body');
    $table->string('channel')->nullable();
    $table->string('status')->default('draft'); // draft, copied, sent
    $table->timestamps();
});

// 024_create_compliance_profiles
Schema::create('compliance_profiles', function (Blueprint $table) {
    $table->id();
    $table->foreignId('player_id')->constrained()->onDelete('cascade');
    $table->date('date_of_birth')->nullable();
    $table->string('location')->nullable();
    $table->string('id_verification_status')->default('not_verified');
    $table->timestamp('id_verified_at')->nullable();
    $table->text('compliance_notes')->nullable();
    $table->timestamps();
});

// 025_create_exclusions
Schema::create('exclusions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('player_id')->constrained()->onDelete('cascade');
    $table->string('type');                     // cool_off, self_exclusion, admin_suspension
    $table->timestamp('starts_at');
    $table->timestamp('ends_at')->nullable();
    $table->text('reason')->nullable();
    $table->foreignId('created_by')->constrained('agents');
    $table->timestamps();
});
```

---

## 4. Models & Key Relationships

```php
// Agent.php (extends Authenticatable)
class Agent extends Authenticatable
{
    protected $casts = ['role' => AgentRole::class, 'active' => 'boolean'];

    public function players(): HasMany;               // Agent's assigned players
    public function assignedPlayers(): HasMany;        // As admin
    public function createdNotes(): HasMany;
    public function createdEntries(): HasMany;
    public function tickets(): HasMany;               // Assigned tickets
    public function isOwner(): bool;
    public function isManager(): bool;
    public function isAccountant(): bool;
    public function isAuditor(): bool;
    public function canViewPlayer(Player $player): bool;         // Policy check
    public function canViewPlayerFinancials(Player $player): bool;
}

// Player.php
class Player extends Model
{
    protected $casts = [
        'status' => PlayerStatus::class,
        'compliance_complete' => 'boolean',
        'risk_status' => RiskLevel::class,
    ];

    public function platformAccounts(): HasMany;
    public function tags(): BelongsToMany;
    public function notes(): HasMany;
    public function agent(): BelongsTo;
    public function assignedAdmin(): BelongsTo;
    public function ledgerLines(): HasMany;
    public function sessions(): HasMany;
    public function tickets(): HasMany;
    public function promoRedemptions(): HasMany;
    public function riskFlags(): HasMany;
    public function compliance(): HasOne;
    public function exclusions(): HasMany;

    // Computed from ledger
    public function balance(): float;
    public function lifetimeVolume(): float;
    public function isExcluded(): bool;
}

// LedgerEntry.php — IMMUTABLE after creation
class LedgerEntry extends Model
{
    protected $casts = ['locked' => 'boolean'];

    public function lines(): HasMany;
    public function creator(): BelongsTo(Agent::class);
    public function source(): MorphTo;                  // Player, Agent, etc.
    public function reversedEntry(): BelongsTo;
    public function reversalFor(): HasOne;               // The entry this reverses
    public function isBalanced(): bool;
    public function lock(): void;
}
```

---

## 5. Policies (Laravel Authorization)

```php
// PlayerPolicy.php
class PlayerPolicy
{
    // Owner: all operations on all players
    // Manager: view/edit all players, can't delete
    // Agent: view/edit only assigned players
    // Support: view only, assigned players
    // Accountant: view financial fields only (balance, volume, ledger)
    // Auditor: read-only on everything

    public function viewAny(Agent $agent): bool;     // All except pure Agent (filtered in controller)
    public function view(Agent $agent, Player $player): bool;
    public function create(Agent $agent): bool;       // Owner, Manager, Agent
    public function update(Agent $agent, Player $player): bool;
    public function delete(Agent $agent, Player $player): bool;  // Owner only, soft delete
    public function viewFinancials(Agent $agent, Player $player): bool; // Owner, Manager, Accountant
    public function manageStatus(Agent $agent, Player $player): bool;   // Owner, Manager only
}

// LedgerEntryPolicy.php
class LedgerEntryPolicy
{
    public function viewAny(Agent $agent): bool;      // All except Support
    public function view(Agent $agent, LedgerEntry $entry): bool;
    public function create(Agent $agent): bool;       // Owner, Manager, Accountant
    public function void(Agent $agent, LedgerEntry $entry): bool;  // Owner only
    // No update or delete. Entries are immutable.
}

// SupportTicketPolicy.php
class SupportTicketPolicy
{
    public function viewAny(Agent $agent): bool;
    public function view(Agent $agent, SupportTicket $ticket): bool;
    public function create(Agent $agent): bool;       // All roles
    public function update(Agent $agent, SupportTicket $ticket): bool;
}
```

---

## 6. Services (Business Logic Layer)

### LedgerService
Every money movement goes through this service. Single source of truth.

```php
class LedgerService
{
    // Create an immutable ledger entry with balancing lines
    public function createEntry(
        string $type,
        string $description,
        Agent $createdBy,
        array $lines,                           // [[account_id, player_id?, debit, credit], ...]
        ?Model $source = null,                  // Polymorphic source
        ?string $reference = null,
        ?Carbon $entryDate = null
    ): LedgerEntry;

    // Reverse an existing entry (creates opposite amounts, cross-references)
    public function voidEntry(
        LedgerEntry $entry,
        Agent $agent,
        string $reason
    ): LedgerEntry;

    // Get player's current balance from all non-reversed lines
    public function getPlayerBalance(Player $player): float;

    // Get ledger account balance at a point in time
    public function getAccountBalance(LedgerAccount $account, ?Carbon $asOf = null): float;

    // Lock a day — no new entries permitted for that date
    public function dailyClose(Carbon $date, Agent $agent): DailyClose;

    // Run reconciliation for a date
    public function reconcile(
        Carbon $date,
        float $platformTotal,
        Agent $agent
    ): Reconciliation;

    // Verify all entries balance (integrity check)
    public function auditLedger(): array;
}
```

### Immutability Rules (enforced in Service)
1. `LedgerEntry` rows never change after creation.
2. `LedgerLine` rows never change after creation.
3. Corrections = new reversal entries with cross-reference to original.
4. `daily_close` date lock prevents new entries for that date.
5. Entry numbers are sequential: `YYYYMMDD-XXXXX`.
6. Large/sensitive entries can require owner/manager approval.

### AuditService
```php
class AuditService
{
    public function log(
        Agent $agent,
        string $action,
        Model $auditable,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null
    ): AuditLog;

    public function logSensitiveAction(
        Agent $agent,
        string $action,
        Model $auditable,
        array $context
    ): AuditLog;
}
```

### PlayerCrmService
```php
class PlayerCrmService
{
    public function addNote(Player $player, Agent $agent, string $note, ?string $category): PlayerNote;
    public function addTag(Player $player, string $tag): PlayerTag;
    public function updateStatus(Player $player, PlayerStatus $status, Agent $agent, ?string $reason): void;
    public function syncPlatformAccounts(Player $player, array $accounts): void;
    public function flagRisk(Player $player, Agent $agent, string $type, string $description, string $severity): RiskFlag;
}
```

---

## 7. Form Requests (Validation)

```php
// StorePlayerRequest
class StorePlayerRequest extends FormRequest
{
    public function rules(): array {
        return [
            'name' => 'required|string|max:255',
            'preferred_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:' . PlayerStatus::values(),
            'referral_source' => 'nullable|string|max:255',
            'agent_id' => 'nullable|exists:agents,id',
            'platform_accounts' => 'nullable|array',
            'platform_accounts.*.platform' => 'required_with:platform_accounts|in:ClubGG,PPPoker,PokerBros',
            'platform_accounts.*.username' => 'required_with:platform_accounts|string|max:255',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ];
    }
}

// StoreLedgerEntryRequest
class StoreLedgerEntryRequest extends FormRequest
{
    public function rules(): array {
        return [
            'type' => 'required|in:' . TransactionType::values(),
            'description' => 'required|string|max:1000',
            'entry_date' => 'required|date|before_or_equal:today',
            'source_type' => 'required|in:player,agent',
            'source_id' => 'required|integer',
            'lines' => 'required|array|min:2',
            'lines.*.account_id' => 'required|exists:ledger_accounts,id',
            'lines.*.player_id' => 'nullable|exists:players,id',
            'lines.*.debit' => 'required_without:lines.*.credit|numeric|min:0|max:999999999',
            'lines.*.credit' => 'required_without:lines.*.debit|numeric|min:0|max:999999999',
            'reference' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:jpg,png,pdf|max:10240',
        ];
    }
}
```

---

## 8. Routes

### Web Routes (`routes/web.php`)

```php
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Player CRM
    Route::resource('players', PlayerController::class);
    Route::post('players/{player}/notes', [PlayerNoteController::class, 'store']);
    Route::post('players/{player}/tags', [PlayerTagController::class, 'store']);
    Route::delete('players/{player}/tags/{tag}', [PlayerTagController::class, 'destroy']);
    Route::post('players/{player}/platform-accounts', [PlatformAccountController::class, 'store']);
    Route::post('players/{player}/risk-flags', [RiskFlagController::class, 'store']);
    Route::post('players/{player}/contacted', [PlayerController::class, 'markContacted']);

    // Agents
    Route::resource('agents', AgentController::class)->middleware('role:owner,manager');

    // Ledger
    Route::resource('ledger/accounts', LedgerAccountController::class);
    Route::get('ledger/entries', [LedgerEntryController::class, 'index'])->name('ledger.entries');
    Route::post('ledger/entries', [LedgerEntryController::class, 'store']);
    Route::get('ledger/entries/{entry}', [LedgerEntryController::class, 'show']);
    Route::post('ledger/entries/{entry}/void', [LedgerEntryController::class, 'voidEntry']);
    Route::get('ledger/player/{player}/balance', [LedgerController::class, 'playerBalance']);

    // Reconciliation
    Route::get('reconciliations', [ReconciliationController::class, 'index']);
    Route::post('reconciliations', [ReconciliationController::class, 'store']);
    Route::get('reconciliations/{reconciliation}', [ReconciliationController::class, 'show']);
    Route::post('reconciliations/{reconciliation}/lock', [ReconciliationController::class, 'lock']);

    // Games
    Route::resource('games', GameController::class);

    // Promotions
    Route::resource('promotions', PromotionController::class);
    Route::post('promotions/{promotion}/redeem/{player}', [PromotionController::class, 'redeem']);

    // Support Tickets
    Route::resource('tickets', SupportTicketController::class);
    Route::post('tickets/{ticket}/comments', [TicketCommentController::class, 'store']);

    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('player-statement/{player}', [ReportController::class, 'playerStatement']);
        Route::get('daily-ledger/{date?}', [ReportController::class, 'dailyLedger']);
        Route::get('daily-close/{date?}', [ReportController::class, 'dailyClose']);
        Route::get('promo-liability', [ReportController::class, 'promoLiability']);
        Route::get('agent-exposure/{agent?}', [ReportController::class, 'agentExposure']);
        Route::get('open-disputes', [ReportController::class, 'openDisputes']);
        Route::get('ledger-exceptions', [ReportController::class, 'ledgerExceptions']);
        Route::get('activity-by-platform', [ReportController::class, 'activityByPlatform']);
    });

    // Imports
    Route::get('imports', [ImportController::class, 'index']);
    Route::post('imports', [ImportController::class, 'store']);
    Route::get('imports/{import}', [ImportController::class, 'show']);
    Route::post('imports/{import}/accept', [ImportController::class, 'acceptRow']);
    Route::post('imports/{import}/skip', [ImportController::class, 'skipRow']);

    // Communications
    Route::resource('templates', CommunicationTemplateController::class);
    Route::get('compose/{template}/{player?}', [CommunicationController::class, 'compose']);

    // Attachments
    Route::get('attachments/{attachment}/download', [AttachmentController::class, 'download']);
    Route::post('attachments/upload', [AttachmentController::class, 'store']);

    // Audit Log
    Route::get('audit-log', [AuditLogController::class, 'index']);

    // Compliance
    Route::get('compliance', [ComplianceController::class, 'index']);
    Route::get('compliance/players/{player}', [ComplianceController::class, 'show']);
    Route::post('compliance/players/{player}/exclude', [ComplianceController::class, 'excludePlayer']);
    Route::post('compliance/players/{player}/reinstate', [ComplianceController::class, 'reinstatePlayer']);

    // Settings
    Route::get('settings', [SettingsController::class, 'index']);
});
```

### API Routes (`routes/api.php`) — Future Native App
```php
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('dashboard', [Api\DashboardController::class, 'index']);
    Route::apiResource('players', Api\PlayerController::class);
    Route::apiResource('ledger/entries', Api\LedgerEntryController::class);
    Route::apiResource('reconciliations', Api\ReconciliationController::class);
    Route::apiResource('promotions', Api\PromotionController::class);
    Route::apiResource('tickets', Api\TicketController::class);
    Route::apiResource('games', Api\GameController::class);
    Route::apiResource('templates', Api\TemplateController::class);
    Route::post('uploads', [Api\UploadController::class, 'store']);
});
```

---

## 9. Dashboard & Widgets

### Desktop Dashboard
```
Dashboard (role-filtered widgets)
├── KPI Row
│   ├── Active Players (today)
│   ├── New Leads (this week)
│   ├── Pending Onboarding
│   ├── Unresolved Tickets
│   ├── Pending Ledger Items
│   ├── Reconciliation Mismatches
│   ├── Promo Liability (current)
│   ├── Agent/Player Exposure Alerts
│   ├── Dormant VIPs (30+ days)
│   └── Daily Close Status (locked/open)
├── Tables
│   ├── Recent Player Activity (last 10)
│   ├── Recent Ledger Entries (last 10)
│   ├── Open Support Tickets
│   └── Tonight's Games
└── Quick Actions
    ├── Add Player
    ├── New Ledger Entry
    ├── New Ticket
    ├── Upload Screenshot
    ├── Copy Tonight's Game Message
    ├── Run Reconciliation
    └── Start Daily Close
```

### Mobile Dashboard
Simplified "what needs attention" screen, usable in under 30 seconds from a phone:
- Pending player approvals
- New leads
- Player follow-ups
- Ledger items needing review
- Agent/player exposure alerts
- Promo redemptions
- Open support tickets
- Tonight's games
- Daily close status

### Mobile Quick Actions
Large touch-friendly buttons:
- Add Player
- Add Note
- Add Ledger Entry
- Upload Screenshot
- Create Support Ticket
- Start Reconciliation
- Copy Tonight's Game Message
- Mark Player Contacted
- Apply Promo
- Flag Risk Issue

---

## 10. Mobile PWA Strategy

### Manifest
```json
{
    "name": "ClubOps OS",
    "short_name": "ClubOps",
    "description": "Private poker club operations system",
    "start_url": "/",
    "display": "standalone",
    "background_color": "#0f172a",
    "theme_color": "#2563eb",
    "icons": [
        { "src": "/icons/icon-192.png", "sizes": "192x192", "type": "image/png" },
        { "src": "/icons/icon-512.png", "sizes": "512x512", "type": "image/png" }
    ]
}
```

### Mobile Navigation (Bottom Tabs)
```
[Home] [Players] [Ledger] [Games] [More]
```
**More menu:** Promotions, Tickets, Imports, Reports, Settings, Compliance, Logout

### Mobile Layout
- Responsive, mobile-first Bootstrap 5 layout
- Sticky bottom navigation bar
- Touch-friendly forms with large targets (min 44x44px)
- Camera/photo capture via `<input type="file" accept="image/*" capture="environment">`
- One-handed form designs
- Fast page loads via server-side rendering + PWA caching

### Mobile Player Profile
Shows: name, platform usernames, status, tags, assigned agent, balance (if permitted), last played, last contact, promo history, support tickets, notes timeline, risk flags.

Quick buttons: Call/Text/Copy Contact, Add Note, Add Transaction, Create Ticket, Upload Screenshot, Mark Contacted, Suspend/Flag (owner/manager only).

### Mobile Agent View
Agents see only assigned players. Dashboard: My Active Players, My Leads, Players Needing Follow-up, Open Tickets, Promo Redemptions, Exposure/Stop-Limit Warnings, Copy/Paste Message Templates.

### Mobile Ledger Entry
Low-friction but safe. Required: Player/Agent, Transaction Type, Amount, Reason, Source/Platform. Optional: Attachment. Confirmation screen before save.

### Mobile Upload
Camera capture or gallery select. Attach to: Player, Ticket, Ledger Entry, Reconciliation, Import. Optional short note. Stored privately, served via authenticated routes.

### Mobile Reconciliation
Desktop preferred, but mobile supports: Enter platform total, Upload screenshot/export, View mismatch, Add note, Mark "needs desktop review", Owner can lock daily close (after all exceptions resolved).

### Mobile Communications Hub
Message template library with player-specific merge fields. Copy to clipboard. Tone selector: Friendly, Professional, Short/Direct, VIP, Fun Poker-Club Style. Channel labels: SMS, Telegram, Discord, WhatsApp, Email. No spam automation — human-approved copy/paste workflows.

### Service Worker
```javascript
const CACHE = 'clubops-v1';
const URLS = ['/', '/offline', '/manifest.json', '/icons/icon-192.png'];

self.addEventListener('install', (e) => {
    e.waitUntil(caches.open(CACHE).then(c => c.addAll(URLS)));
    self.skipWaiting();
});

self.addEventListener('fetch', (e) => {
    // Network-first for dynamic pages, cache-first for static
    if (e.request.url.includes('/icons/') || e.request.url.endsWith('manifest.json')) {
        e.respondWith(caches.match(e.request).then(r => r || fetch(e.request)));
    }
});
```

### Offline Behavior (MVP)
- App shell loads quickly
- Important pages mobile-optimized
- No full offline ledger writing in MVP
- Later: Draft notes/tickets/player updates offline, sync when connection returns
- Never allow offline ledger commits without server confirmation

---

## 11. Reconciliation Workflow

```
1. Agent enters or imports platform totals for the day
2. Agent uploads screenshot/export from ClubGG/PPPoker/PokerBros
3. System compares platform totals against ClubOps ledger totals
4. System shows matched and unmatched items
5. Agent reviews mismatches
6. Agent creates correction/reversal entries for any discrepancies
7. Attach screenshots/evidence to each mismatch
8. Agent resolves all exceptions
9. Owner or Manager locks the day (daily close)
10. System generates daily close report
11. Locked day: no further normal edits permitted
```

---

## 12. Promotion Engine

### Models
```
Promotion
├── name, type, description
├── value, cap
├── total_liability, claimed_liability
├── starts_at, ends_at
├── active
├── terms
├── eligibility_rules (JSON)

PromotionRule
├── promotion_id
├── field (player_status, game_type, minimum_volume, etc.)
├── operator (equals, gt, gte, in)
├── value

PromotionRedemption
├── promotion_id
├── player_id
├── ledger_entry_id
├── amount
├── status (pending, approved, denied, voided)
├── claimed_at
```

### Tracked Metrics
- Eligibility
- Start/end date
- Cap
- Total liability
- Claimed liability
- Individual redemptions
- Abuse flags
- ROI notes (free-text)

### Supported Promo Types
- Welcome bonus
- Referral bonus
- Reactivation offer
- VIP offer
- Tournament/leaderboard promo
- Game-starting incentive
- Volume/activity reward tracking (where legal and allowed)

---

## 13. Support / Disputes

### Ticket Types
- Ledger question
- Promo issue
- Platform access
- Game complaint
- Collusion/cheating concern (handled carefully — no false accusations)
- Behavior issue
- Disconnection issue
- Other

### Ticket Data Model
```
SupportTicket
├── ticket_number (TKT-XXXXX)
├── player_id
├── assigned_to (agent)
├── type (TicketType)
├── subject
├── description
├── priority
├── status (open, in_progress, resolved, closed)
├── resolved_at

TicketComment
├── ticket_id
├── author (morph: Agent or Player)
├── body

Attachments (polymorphic)
├── attachable (Ticket, Player, LedgerEntry, etc.)
├── uploaded_by
├── filename, mime_type, size
```

---

## 14. Communications Hub

Not a spam system. Template library + human copy/paste workflows.

### Templates
```php
[
    'name' => 'Welcome Message',
    'category' => 'welcome',
    'channel' => 'telegram',
    'body' => "Hey {player_name}! Welcome to the club. Here's how to get started...",
    'tone' => 'friendly',        // friendly, professional, short, vip, fun
    'merge_fields' => 'player_name,club_name,game_time'
]
```

### Template Categories
- Welcome message
- Join instructions
- Rules reminder
- Tonight's games
- VIP check-in
- Dormant player reactivation
- Promo announcement
- Support response
- Dispute resolution
- Responsible gaming / cool-off message

### Tone Options
| Tone | When to Use |
|------|------------|
| Friendly | Day-to-day engagement |
| Professional | Official/compliance communication |
| Short/Direct | Action-oriented, known players |
| VIP Concierge | High-value players, special treatment |
| Fun Poker-Club Style | Game reminders, banter |

### Workflow
1. Select template
2. Pick tone
3. System merges player-specific fields
4. Agent reviews and edits
5. Copy to clipboard
6. Paste into SMS/Telegram/Discord/WhatsApp/Email
7. No API auto-sending. Human approval for every message.

---

## 15. Imports

### MVP Import Sources
- CSV file upload
- Manual form entry
- Screenshot/document attachment

### Import Workflow
```
1. Upload CSV file
2. Preview screen shows raw data
3. Admin maps CSV columns to database fields
4. System validates rows
5. Admin approves, skips, or flags each row
6. Accepted rows are imported
7. Import summary generated
```

### Import Types
```php
'type' => 'players'         // Bulk player import
'type' => 'ledger'          // Bulk ledger entries (from export)
'type' => 'reconciliation'  // Platform totals import
```

### Later Import Sources
- Hand-history / converter import
- PokerCraft export import
- Google Sheets export/import
- Screenshot OCR (basic)

---

## 16. Compliance / Safety

This is a first-class feature, not an afterthought.

### Rules (Non-Negotiable, Displayed on Settings Page)
- **No payment processing.** The system tracks operational ledgers only.
- **No automatic chip loading or cashout.** No integration with payment gateways.
- **No scraping or botting** of poker platform apps.
- **No evasion** of platform terms of service.
- **No laundering or concealment workflows.** All ledgers are auditable.
- **Label ledger as operational recordkeeping** — not financial accounting.
- **Include responsible gaming statuses:** cool-off, self-exclusion, admin suspension.
- **Require age/location/compliance notes** where applicable (track, don't enforce).
- **Keep audit logs** for all sensitive actions.
- **No permanent deletion of ledger records.** Ever.

### Compliance Profiles
```php
ComplianceProfile
├── player_id
├── date_of_birth
├── location
├── id_verification_status (not_verified, pending, verified, rejected)
├── id_verified_at
├── compliance_notes
```

### Exclusions
```php
Exclusion
├── player_id
├── type (cool_off, self_exclusion, admin_suspension)
├── starts_at
├── ends_at (nullable — indefinite)
├── reason
├── created_by
```
Excluded players are flagged everywhere in the system. Ledger entries for excluded players require owner approval.

---

## 17. Reports

| Report | Format | Contents |
|--------|--------|----------|
| Daily Close | HTML + CSV | Opening balance, all entries, closing balance, lock status |
| Player Statement | HTML + CSV | All ledger activity for a player, date-ranged |
| Agent Exposure | HTML + CSV | Per-agent: active players, total balances, risk flags |
| Promo Liability | HTML + CSV | All active promotions, remaining cap, claimed amounts |
| Open Disputes | HTML + CSV | All tickets with type=complaint, unresolved |
| Ledger Exceptions | HTML + CSV | Unbalanced entries, reversal cross-reference issues |
| Activity by Platform | HTML + CSV | Volume by platform (ClubGG, PPPoker, PokerBros) |
| Ledger Full Audit | CSV only | Every entry + lines for accountant/auditor |

All reports export as CSV. No PDF in MVP (CSV + screen-print-to-PDF suffices).

---

## 18. Build Phases

### Phase 1 — Foundation (Week 1)
```
□ Laravel project scaffold (composer create-project)
□ Breeze auth (Blade stack)
□ MySQL database creation
□ All migrations (025 tables)
□ Run migrations
□ Seed initial data (admin owner, base roles, chart of accounts)
□ AuditService with helper trait
□ Basic admin Blade layout + Bootstrap 5
□ DreamHost deployment test
□ PWA manifest + service worker + icons
□ Mobile responsive layout
```

### Phase 2 — CRM (Week 2)
```
□ Agent CRUD with roles
□ Player CRUD with status workflows
□ Player platform accounts
□ Player tags
□ Player notes
□ Lead intake
□ Player compliance profiles
□ Player risk flags
□ Exclusions
□ Agent role policies
```

### Phase 3 — Ledger (Week 3)
```
□ LedgerAccount CRUD (chart of accounts)
□ LedgerEntry + LedgerLine creation (immutable)
□ Balance computation
□ Manual transaction form
□ Reversal/void workflow
□ Entry number generation
□ Player balance display
□ Accountant role access
```

### Phase 4 — Reconciliation (Week 4)
```
□ Daily reconciliation form
□ Platform total entry
□ Screenshot/export upload
□ Ledger comparison
□ Mismatch display
□ Correction entry workflow
□ Daily close lock
□ Daily close report (HTML + CSV)
□ Dashboard KPI: close status
```

### Phase 5 — Promotions & Support (Week 5)
```
□ Promotion CRUD
□ Promotion redemption workflow
□ Promo liability tracking
□ Promo abuse flags
□ Support ticket CRUD
□ Ticket comments
□ Ticket assignment
□ Communication templates
□ Message compose + copy workflow
□ Tone selector
```

### Phase 6 — Reports & Polish (Week 6)
```
□ All KPI dashboard cards
□ Player statement report
□ Agent exposure report
□ Promo liability report
□ Open disputes report
□ Ledger exceptions report
□ Platform activity report
□ CSV exports on all reports
□ Touch-friendly mobile nav
□ Mobile quick actions
□ Camera/photo upload
□ Mobile dashboard
□ GitHub repo documentation
```

---

## 19. Deployment to DreamHost

### Prerequisites
- DreamHost shared hosting with PHP 8.2+ and MySQL 8+
- SSH access to DreamHost
- Composer installed on DreamHost (or installation instructions)
- Custom domain or subdomain pointed to DreamHost
- Git installed on DreamHost (or use rsync)

### Step-by-Step Deployment

```bash
# 1. Create DreamHost MySQL database
# DreamHost Panel → Goodies → MySQL Databases
# Database name: clubops_db
# Username: clubops_user

# 2. Connect via SSH
ssh user@example.dreamhost.com

# 3. Check PHP version
/usr/bin/php8.2 -v
ls /usr/bin/php*   # Find available PHP versions

# 4. Install Composer (if not present)
cd ~
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=~/bin
php -r "unlink('composer-setup.php');"

# 5. Clone or upload the app
cd ~/clubops.example.com
git clone https://github.com/generic007/clubops.git .
# OR rsync from local:
# rsync -avz --exclude node_modules --exclude .git --exclude .env ./ user@example.dreamhost.com:~/clubops.example.com/

# 6. Install PHP dependencies (no dev)
composer install --no-dev --optimize-autoloader

# 7. Create .env file
cp .env.example .env
# Edit .env:
# APP_ENV=production
# APP_DEBUG=false
# APP_URL=https://clubops.example.com
# DB_DATABASE=clubops_db
# DB_USERNAME=clubops_user
# DB_PASSWORD=your_password

# 8. Generate app key
php artisan key:generate

# 9. Run migrations
php artisan migrate --force

# 10. Seed initial admin
php artisan db:seed --class=AdminSeeder

# 11. Set directory permissions
chmod -R 775 storage bootstrap/cache
chmod -R 775 public/uploads

# 12. Point domain to Laravel
# DreamHost Panel → Manage Domains → Web Directory
# Set document root to: /home/USER/clubops.example.com/public
```

### DreamHost Cron Configuration
```
# DreamHost Panel → Advanced → Cron Jobs
# Add job:
Command: cd /home/USER/clubops.example.com && /usr/bin/php8.2 artisan schedule:run >> /dev/null 2>&1
Frequency: Every 5 minutes
```

### Backup Strategy
```bash
# Database backup script
#!/bin/bash
BACKUP_DIR=~/backups
mkdir -p $BACKUP_DIR
DATE=$(date +%Y%m%d)

# Dump database
mysqldump -u clubops_user -p clubops_db > $BACKUP_DIR/clubops-$DATE.sql

# Archive uploads
tar -czf $BACKUP_DIR/clubops-uploads-$DATE.tar.gz -C ~/clubops.example.com storage/app/private

# Keep 30 days of backups
find $BACKUP_DIR -name "*.sql" -mtime +30 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +30 -delete
```

### Safe Update Process
```bash
# 1. Backup database
# 2. Backup storage/app/private/
# 3. Put app in maintenance mode
php artisan down --retry=60

# 4. Pull latest code
git pull origin main

# 5. Update dependencies
composer install --no-dev --optimize-autoloader

# 6. Run new migrations
php artisan migrate --force

# 7. Clear caches
php artisan optimize:clear

# 8. Bring app back up
php artisan up

# 9. Verify everything works
```

### Important DreamHost Notes
- Use explicit PHP version: `/usr/bin/php8.2` (check with `ls /usr/bin/php*`)
- `storage/` and `bootstrap/cache/` must be writable by web server user
- File uploads go to `storage/app/private/` — outside public web root
- Serve uploaded files via Laravel controller, never direct URL access
- `.env` must be manually created on DreamHost (never committed to git)
- DreamHost cron minimum frequency is 5 minutes

---

## 20. Future Native App Strategy

### When to Build
Only after:
1. Laravel PWA is stable and used daily
2. Business validates mobile app store distribution is needed
3. Core users request native features (push notifications, offline ledger writing, biometric auth)

### API Endpoints (for native app)
```
POST   /api/v1/auth/login
POST   /api/v1/auth/logout
GET    /api/v1/dashboard
GET    /api/v1/players
POST   /api/v1/players
GET    /api/v1/players/{id}
PUT    /api/v1/players/{id}
GET    /api/v1/agents
GET    /api/v1/ledger/entries
POST   /api/v1/ledger/entries
POST   /api/v1/ledger/entries/{id}/void
GET    /api/v1/reconciliations
POST   /api/v1/reconciliations
GET    /api/v1/promotions
POST   /api/v1/promotions/{id}/redeem
GET    /api/v1/tickets
POST   /api/v1/tickets
GET    /api/v1/games
POST   /api/v1/uploads
GET    /api/v1/templates
```

### Native Options (Ranked)
1. **Capacitor** — Easiest if PWA is already good. Wrap into app-store shells.
2. **Flutter** — Best polished native UI. More work. Uses Laravel API.
3. **React Native** — Good if team prefers JS/TS. More work than PWA+Capacitor.

### Recommendation
Do not build native first. Build in order:
1. ✅ Laravel responsive web app (MVP)
2. ✅ Installable PWA (Phase 1)
3. □ Laravel API (Phase 2)
4. □ Optional Capacitor wrapper (Phase 3)
5. □ Native Flutter/React Native (only if business needs App Store distribution)

---

## 21. Acceptance Criteria

### Platform
- [ ] App runs on DreamHost PHP 8.2+ MySQL hosting
- [ ] No Node server required in production
- [ ] No Supabase/Postgres required
- [ ] No Redis/WebSockets required
- [ ] No Docker required

### Core Features (Owner)
- [ ] Owner can add players, agents, notes, tags, platform accounts
- [ ] Owner can create immutable ledger entries
- [ ] Owner can reverse/void ledger entries
- [ ] Owner can reconcile daily totals
- [ ] Owner can lock a daily close
- [ ] Owner can track promotions and support tickets

### Permissions
- [ ] Agent permissions restricted to assigned players only
- [ ] Accountant sees financial data only
- [ ] Auditor has read-only access
- [ ] Audit log captures all sensitive actions
- [ ] Policies gate every controller action

### Mobile
- [ ] App works well on iPhone and Android browser
- [ ] App is installable to home screen as PWA
- [ ] Owner can review dashboard from phone
- [ ] Agent can manage assigned players from phone
- [ ] Agent can add notes from phone
- [ ] Agent can upload screenshots from phone
- [ ] Agent can create support tickets from phone
- [ ] Agent can create ledger entries with confirmation step
- [ ] Agent can copy/paste messages from phone
- [ ] Sensitive actions are permission-checked and audit-logged
- [ ] Touch targets are at least 44x44px
- [ ] Bottom navigation is sticky and usable one-handed

### Compliance
- [ ] No payment processing in the system
- [ ] No scraping or botting functionality
- [ ] No platform term evasion workflows
- [ ] Ledger labeled as operational recordkeeping
- [ ] Responsible gaming statuses available (cool-off, exclusion)
- [ ] No permanent deletion of ledger records

### Quality
- [ ] Reports export as CSV
- [ ] All DECIMAL fields are DECIMAL(18,2) or DECIMAL(20,4), never FLOAT
- [ ] Soft deletes on CRM records
- [ ] Immutable ledger entries with reversal workflow
- [ ] README clear enough for DreamHost deployment by a non-expert

---

## 22. Reference: Artisan Commands

```bash
# Daily close
php artisan clubops:daily-close

# Send monthly player statements (scheduled)
php artisan clubops:send-monthly-statements

# Recalculate all account balances from scratch
php artisan clubops:recalculate-balances

# Export full player list as CSV
php artisan clubops:export-players

# Check dormant players + notify
php artisan clubops:check-dormant-players

# Verify ledger integrity (all entries balance)
php artisan clubops:audit-ledger

# Generate demo data for testing
php artisan db:seed --class=DemoSeeder

# Import from CSV (CLI mode)
php artisan clubops:import --type=players --file=players.csv

# Generate API docs
php artisan clubops:generate-api-docs
```

---

## 23. Key Principles (Non-Negotiable)

1. **No destructive deletes on financial data.** Ledger entries are immutable. Corrections = reversals.
2. **All money uses DECIMAL(18,2) or DECIMAL(20,4).** Never FLOAT or DOUBLE. Never.
3. **Sensitive actions go to audit_log.** Always. Create, update status, void entries, close day, reconcile.
4. **Policies gate everything.** No controller bypasses authorization.
5. **Every ledger entry has an actor, timestamp, and reason.** No orphaned transactions.
6. **Export over API.** Reports generate HTML + CSV. No real-time API endpoints for MVP.
7. **Boring tech wins.** Laravel + MySQL + Blade. Not Livewire where Alpine.js suffices.
8. **Deploy first, optimize later.** Ship the ugly working version. Polishing happens after it's live.
9. **Mobile-first responsive.** Design for phone first, desktop second.
10. **Compliance is architecture, not a checkbox.** Data isolation, audit trails, exclusions, and responsible gaming are built into the schema.
