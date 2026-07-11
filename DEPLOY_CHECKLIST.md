# ClubOps OS — Deployment + Launch Checklist

## Pre-Deployment
- [ ] Stripe .env keys configured (test mode first)
- [ ] SubscriptionPlan seeder run
- [ ] CheckSubscription middleware registered
- [ ] All billing routes verified (`php artisan route:list | grep billing`)
- [ ] BillingController tests pass
- [ ] Migration rollback tested
- [ ] .env backup created

## Deployment to DreamHost
- [ ] `git add -A && git commit -m "feat: add Stripe subscription gate"`
- [ ] SSH to DreamHost
- [ ] `cd ~/clubops.juncturelogic.com && git pull`
- [ ] `composer install --no-dev --optimize-autoloader`
- [ ] `php artisan migrate`
- [ ] `php artisan db:seed --class=SubscriptionPlanSeeder`
- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `php artisan view:cache`
- [ ] Verify login page loads
- [ ] Verify pricing page loads
- [ ] Verify Stripe Checkout redirect works (test mode)

## Post-Deployment
- [ ] Change Stripe from test → live keys
- [ ] Test complete checkout flow end-to-end
- [ ] Test login with and without subscription
- [ ] Test logout
- [ ] Verify no debug output visible
- [ ] Verify error pages don't leak stack traces
- [ ] Check `storage/logs/laravel.log` for errors

## Launch Assets
- [ ] Pricing page live at clubops.juncturelogic.com/billing
- [ ] Login page references subscription
- [ ] "Powered by ClubOps" badge (optional)

## Analytics
- [ ] Plausible self-hosted or simple page view counter
- [ ] Event tracking for: page views, login attempts, checkout starts, checkout completes

## Monitoring
- [ ] Health endpoint returns 200
- [ ] Error logging enabled
- [ ] Database backups verified (DreamHost auto-backup)
