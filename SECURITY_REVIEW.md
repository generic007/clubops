# ClubOps OS — Security Review

**Date:** 2026-07-01  
**Reviewer:** Security Audit Subagent  
**Scope:** All application code (`app/`, `resources/views/`, `routes/`, `config/`, `database/`)  
**Methodology:** Static code analysis of 100% of PHP files and Blade templates

---

## Finding Summary

| # | Category | Severity | Status |
|---|----------|----------|--------|
| 1 | Authorization — Missing Policy Gates | **Critical** | Unresolved |
| 2 | File Upload — Missing MIME Validation | **High** | Unresolved |
| 3 | Rate Limiting — No Throttle on Any Route | **High** | Unresolved |
| 4 | Authorization — ReportController Data Leakage | **High** | Unresolved |
| 5 | Authorization — No Gates on Financial Mutations | **High** | Unresolved |
| 6 | SQL Injection — LIKE Queries (Low Risk) | **Low** | Unresolved |
| 7 | Mass Assignment — All Models Protected | **Info** | Resolved |
| 8 | XSS — No Unsafe Output Found | **Info** | Resolved |
| 9 | CSRF — All Forms Protected | **Info** | Resolved |
| 10 | Soft Delete Safety — Ledger Entries Safe | **Info** | Resolved |
| 11 | Hardcoded Secrets — None Found | **Info** | Resolved |
| 12 | Deployment: `APP_DEBUG=true` Risk | **High** | Unresolved |

---

## 1. [CRITICAL] Authorization — Missing Policy Gates

**FILE:** Multiple controllers (see below)  
**STATUS:** Unresolved

### Issue
The vast majority of controllers rely **only** on the `auth` middleware (which confirms the user is logged in) but **never check WHAT the user is allowed to do**. Any authenticated agent — including Support or Agent roles with limited permissions — can access and modify any data.

### Affected Controllers

| Controller | Missing Gate | Key Impact |
|---|---|---|
| **ReportController** | No authorization on any method | Any agent can view any player's statement, daily ledger, daily close, agent exposure, promos, disputes, and ledger exceptions. **No role scoping.** |
| **PromotionController** | No authorization | Any agent can create, edit, delete promotions, and **redeem promotional credits** (direct financial impact) |
| **ImportController** | No authorization | Any agent can upload CSV imports, accept/reject rows |
| **ComplianceController** | No authorization | Any agent can **exclude or reinstate players** |
| **ReconciliationController** | No authorization | Any agent can create reconciliations and **lock daily close** |
| **DashboardController** | No authorization | Minor — KPI data leakage |
| **AuditLogController** | No authorization | Agents can view all audit logs |
| **LedgerAccountController** | No authorization | Any agent can create/edit/deactivate ledger accounts |
| **TicketCommentController** | No authorization | Any agent can add comments to any ticket |
| **PlayerNoteController** | No authorization | No restriction visible |

### Controllers WITH proper authorization
- **PlayerController** — Uses `$this->authorizeResource()` (maps to `PlayerPolicy`)
- **AgentController** — Uses `$this->middleware('role:owner,manager')` (except show)
- **LedgerEntryController** — Partial: calls `$this->authorize('create', ...)` on store/void, but `index()` and `show()` have no gate

### Fix
Add policy-based authorization or middleware gates to every controller method that reads or mutates data. Minimum:
1. Create `ReportPolicy`, `PromotionPolicy`, `ImportPolicy`, `CompliancePolicy`, `ReconciliationPolicy`, `LedgerAccountPolicy`
2. Add `$this->authorize()` calls or route middleware
3. At minimum, restrict financial operations (promo redemption, daily close, exclusions) to `owner`/`manager` roles

```php
// Example fix for PromotionController::redeem()
public function redeem(Request $request, Promotion $promotion, Player $player)
{
    $this->authorize('redeem', $promotion);
    // ...
}
```

---

## 2. [HIGH] File Upload — Missing MIME Validation

**FILE:** `app/Http/Controllers/AttachmentController.php`  
**ISSUE:** The `store()` method validates only `required|file|max:25600` (25MB max), with **no MIME type restriction**. Attackers can upload PHP scripts, HTML files, executables, or other dangerous content.

```php
// Line 17-18 — No mimes: or mimetypes: rule
'file' => 'required|file|max:25600',
```

While files are stored on the `local` disk (not the `public` disk), the risk remains:
- If the disk configuration ever changes to public, uploaded scripts become web-accessible
- Other services or cron jobs might process these files unsafely

**Compare with ImportController** which properly restricts types:
```php
// app/Http/Controllers/ImportController.php — Good example
'file' => 'required|file|mimes:csv,txt,tsv|max:20480',
```

### Fix
Add MIME type validation appropriate for expected file types:

```php
'file' => 'required|file|mimes:pdf,jpg,jpeg,png,gif,doc,docx,txt|max:25600',
// Or more restrictive for attachments that should be images/docs only:
'mimetypes:image/jpeg,image/png,image/gif,application/pdf'
```

---

## 3. [HIGH] Rate Limiting — No Throttle on Any Route

**FILE:** `app/Http/Kernel.php`, `routes/web.php`, `routes/auth.php`  
**STATUS:** Unresolved

### Issue
The `throttle` middleware is defined (`\Illuminate\Routing\Middleware\ThrottleRequests::class`) and used in the `api` middleware group, but **no web routes have throttle middleware applied**. This includes:

- **Login route** — No brute-force protection
- **All POST/PUT/DELETE routes** — No rate limiting
- **CSV export routes** — No request limiting (could be abused for data scraping)

### Evidence
```php
// Kernel.php — API group has throttle, web group does not
'api' => [
    \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],

// web.php — No throttle middleware on any route group
Route::middleware(['auth'])->group(function () {
    // ... all routes without throttle
});
```

### Fix
Add throttle to auth routes and sensitive web routes:

```php
// In routes/auth.php
Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('throttle:5,1'); // 5 attempts per minute

// In routes/web.php
Route::middleware(['auth', 'throttle:60,1'])->group(function () {
    // ... all routes
});
```

Or add throttle to the web middleware group (with a higher limit):

```php
// Kernel.php
'web' => [
    // ... existing middleware ...
    \Illuminate\Routing\Middleware\ThrottleRequests::class.':60,1',
],
```

---

## 4. [HIGH] Authorization — ReportController Data Leakage

**FILE:** `app/Http/Controllers/ReportController.php`  
**STATUS:** Unresolved

### Issue
`ReportController` has **zero authorization gates**. Any authenticated agent can:

1. `playerStatement(Player $player)` — View any player's full financial history
2. `dailyLedger()` — View complete daily ledger data
3. `dailyClose()` — View complete daily close data
4. `agentExposure($agentId)` — View exposure for any specific agent (or ALL agents with their players, balances, and risk flags)
5. `openDisputes()` — View all dispute entries
6. `ledgerExceptions()` — View all ledger audit exceptions
7. `activityByPlatform()` — View platform account counts

The `agentExposure()` method is particularly risky — passing no agent ID returns **all agents with all their player balances and risk flags**:

```php
// No $agentId and no $request->agent → returns ALL agents
$agents = Agent::where('active', true)
    ->with(['players.riskFlags' => fn($q) => $q->where('status', 'open')])
    ->get();
```

### Fix
```php
// In constructor or individual methods
public function playerStatement(Request $request, Player $player)
{
    $this->authorize('viewFinancials', $player);
    // or restrict to accountant/manager roles
    if (!$request->user()->canViewPlayerFinancials()) {
        abort(403);
    }
}
```

---

## 5. [HIGH] Authorization — No Gates on Financial Mutations

**FILE:** `app/Http/Controllers/LedgerAccountController.php`, `app/Http/Controllers/PromotionController.php`  
**STATUS:** Unresolved

### Issue
Critical financial operations lack role-based authorization:

- **LedgerAccountController** — any agent can create, edit, or deactivate ledger accounts
- **PromotionController::redeem()** — any agent can redeem promotional credits (creates real ledger entries with financial value)

### Fix
Restrict to `owner`/`manager`/`accountant` roles at minimum:

```php
// LedgerAccountController constructor
public function __construct(AuditService $audit, ...) {
    $this->middleware('role:owner,manager,accountant');
}
```

---

## 6. [LOW] SQL Injection — LIKE Queries (Low Risk)

**FILE:** Multiple files  
**STATUS:** Unresolved

### Issue
Several controllers use `LIKE` patterns with **string interpolation** instead of binding:

```php
// PlayerController.php — SQL-like interpolation
$query->where(function ($q) use ($s) {
    $q->where('name', 'like', "%{$s}%")
      ->orWhere('email', 'like', "%{$s}%")
      ->orWhere('phone', 'like', "%{$s}%");
});

// ComplianceController.php — Same pattern
$query->where(function ($q) use ($s) {
    $q->where('name', 'like', "%{$s}%")
      ->orWhere('email', 'like', "%{$s}%");
});
```

### Assessment
**Low risk** — Laravel's Query Builder still parameterizes the `%{$s}%` value when passed as the third argument to `where()`. The `%` wildcards are concatenated into the bound value, not the SQL string. However, it's still better practice to use explicit binding:

```php
$query->where('name', 'like', '%'.$s.'%');  // Same thing, just clearer
```

No true SQL injection exists here, but it's worth documenting for defense-in-depth.

---

## 7. [INFO] Mass Assignment — All Models Protected ✅

**STATUS:** Resolved

All models define explicit `$fillable` arrays. No `$guarded = []` or `Model::unguard()` calls were found. Models checked:

- ✅ `Player` — `$fillable` defined
- ✅ `Agent` — `$fillable` defined  
- ✅ `LedgerEntry` — `$fillable` defined
- ✅ `LedgerLine` — `$fillable` defined
- ✅ `LedgerAccount` — `$fillable` defined
- ✅ `SupportTicket` — `$fillable` defined
- ✅ `TicketComment` — `$fillable` defined
- ✅ `Promotion` — `$fillable` defined
- ✅ `PromotionRedemption` — `$fillable` defined
- ✅ `Attachment` — `$fillable` defined
- ✅ `Reconciliation` — `$fillable` defined
- ✅ `ReconciliationItem` — `$fillable` defined
- ✅ `Import` — `$fillable` defined
- ✅ `ImportRow` — `$fillable` defined
- ✅ `AuditLog` — `$fillable` defined
- ✅ `RiskFlag` — `$fillable` defined
- ✅ `ComplianceProfile` — `$fillable` defined
- ✅ `Exclusion` — `$fillable` defined
- ✅ All other models checked

**No action required.**

---

## 8. [INFO] XSS — No Unsafe Output Found ✅

**STATUS:** Resolved

### Checked
All 30+ Blade templates were reviewed for `{!! !!}` (unescaped output) usage.

### Findings
- All user-controlled data is rendered with `{{ }}` (escaped output)
- The only uses of `{!! !!}` are for **ternary expressions with hardcoded strings** (emoji icons), not user input:
  - `{!! $player->compliance_complete ? '✅ Complete' : '⏳ Pending' !!}`
  - `{!! $acct->verified ? '✅' : '⏳' !!}`
  - `{!! $entry->locked ? '🔒' : '🔓' !!}`

These are safe because the values are boolean-driven, not user-submitted strings.

**No action required.**

---

## 9. [INFO] CSRF — All Forms Protected ✅

**STATUS:** Resolved

- All POST/PUT/DELETE forms include `@csrf`
- The `web` middleware group includes `VerifyCsrfToken`
- All form actions go through `POST` with CSRF token

**No action required.**

---

## 10. [INFO] Soft Delete Safety — Ledger Entries Safe ✅

**STATUS:** Resolved

### Check
- `LedgerEntry` — Does NOT use `SoftDeletes` ✅
- `LedgerLine` — Does NOT use `SoftDeletes` ✅
- `LedgerAccount` — Does NOT use `SoftDeletes` ✅
- `Reconciliation` — Does NOT use `SoftDeletes` ✅

### Models that DO use SoftDeletes (appropriate)
- `Player` — Soft delete is appropriate (player can be "deleted" but ledger history preserved)
- `Agent` — Soft delete is appropriate (user can be deactivated)

**No action required.**

---

## 11. [INFO] Hardcoded Secrets — None Found ✅

**STATUS:** Resolved

- No API keys found in application code
- No database passwords, tokens, or credentials in code
- Passwords are properly hashed with `Hash::make()`
- `.env.example` follows standard Laravel pattern

**No action required.**

---

## 12. [HIGH] Deployment: `APP_DEBUG=true` Risk

**FILE:** `.env` (inferred from bootstrap scripts and error handling)  
**STATUS:** Unresolved

### Issue
If `APP_DEBUG=true` is deployed to production — common during development — Laravel will display full stack traces, SQL queries with parameters, environment variables, and file paths when errors occur. This completely bypasses all other security measures by leaking:

- Database connection details
- Application keys and secrets
- File paths revealing server structure
- SQL queries revealing schema

### Fix
Ensure `APP_DEBUG=false` in production `.env`. Verify no error rendering returns sensitive data:

```bash
# In bootstrap.sh or deploy script
sed -i 's/APP_DEBUG=true/APP_DEBUG=false/' .env
```

---

## Additional Observations

### `CommunicationTemplate::render()` — Template Injection (Low)

**FILE:** `app/Models/CommunicationTemplate.php`

```php
public function render(array $data = []): string
{
    $body = $this->body;
    foreach ($data as $key => $value) {
        $body = str_replace('{'.$key.'}', $value, $body);
    }
    return $body;
}
```

If `$data` values come from user input and the rendered output is displayed without `{{ }}` escaping, this could lead to XSS. Currently it appears the template body is admin-controlled, but if player data is merged in, the output must always be escaped.

### Agent Search Leaks Player Referral Source (Low)

**FILE:** `app/Http/Controllers/AgentController.php` — The `show` method passes player data with `referral_source` fields. Agent-level users may see other agents' referral data.

---

## Summary

| Risk Level | Count | Key Findings |
|---|---|---|
| **Critical** | 1 | Authorization missing from most controllers |
| **High** | 4 | File upload validation, rate limiting, data leakage in reports, APP_DEBUG |
| **Low** | 1 | LIKE query style (defense-in-depth) |
| **Info** | 6 | All resolved (mass assignment, XSS, CSRF, secrets, soft deletes) |

**Critical concern:** The authorization gap (Finding #1) is the most impactful. A Support or Agent role user can currently access any player's financial data, create reconciliations, lock daily closes, exclude/reinstate players, redeem promotions (creating real ledger entries), and view the entire audit log. This should be addressed before production deployment.
