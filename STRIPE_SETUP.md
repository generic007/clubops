# ClubOps OS — Stripe Billing Setup

## Status: Test Mode Active ✅

Stripe test mode products and prices have been created. The payment flow is working.

### What's Configured

| Plan | Monthly | Yearly | Monthly Price ID | Yearly Price ID |
|------|---------|--------|-----------------|-----------------|
| Starter | $99/mo | $990/yr | `price_1TsCUDRAT7IDtgojvfrRwXIn` | `price_1TsCUIRAT7IDtgojLzr7l2xl` |
| Professional | $199/mo | $1,990/yr | `price_1TsCUIRAT7IDtgoj62u6C9Dz` | `price_1TsCUIRAT7IDtgojxx2lpRKo` |

### .env Configuration (on DreamHost)

```ini
STRIPE_KEY=pk_test_51TrotkRAT7IDtgojk8fxUviSzWhP6xD9VkXP7r8ePvNm3EDgYeoMAvyDZRGc78kDWxbWrU855BEwK55Q6UInSYs900zFksBbTa
STRIPE_SECRET=rkcs_test_51TrotkRAT7IDtgojjZm9qDCizogWXnVWwZVabZN1M5AypwyXIrJOlesLDlAaPNvD28mewXShtEN9LxSTIuF8JI00kx2Zgrr0
STRIPE_WEBHOOK_SECRET=   # ← SET THIS MANUALLY
CASHIER_CURRENCY=usd
CASHIER_CURRENCY_LOCALE=en_US
CASHIER_WEBHOOK_URL=https://clubops.juncturelogic.com/stripe/webhook
```

### Manual Step: Set Up Webhook

**The Stripe sandbox restricted key cannot create webhook endpoints.** This requires manual setup:

1. Go to [Stripe Dashboard → Webhooks](https://dashboard.stripe.com/test/webhooks)
2. Click **"Add endpoint"**
3. **Endpoint URL:** `https://clubops.juncturelogic.com/stripe/webhook`
4. **Events to listen for:**
   - `checkout.session.completed`
   - `customer.subscription.deleted`
   - `customer.subscription.updated`
   - `invoice.paid`
   - `invoice.payment_failed`
5. Click **"Add endpoint"**
6. Copy the **"Signing secret"** (starts with `whsec_`)
7. SSH into DreamHost and update .env:
   ```bash
   ssh dreamhost
   sed -i 's|STRIPE_WEBHOOK_SECRET=$|STRIPE_WEBHOOK_SECRET=whsec_YOUR_SECRET_HERE|' ~/clubops.juncturelogic.com/.env
   ```
8. Verify webhook: Stripe Dashboard → Webhooks → "Send test event"

### Testing the Payment Flow

#### Via Stripe Dashboard (no auth required)

1. Open Stripe Dashboard → [Create a Checkout Session](https://dashboard.stripe.com/test/payments/create)
2. Select "Subscription" mode
3. Choose "ClubOps Starter" or "ClubOps Professional"
4. Use test card: `4242 4242 4242 4242` with any future date + any CVC
5. Verify redirect to ClubOps success page

#### Via UI (requires agent login to ClubOps)

1. Log into ClubOps OS
2. Click **"Subscription"** in the sidebar
3. Choose Starter ($99/mo) or Professional ($199/mo)
4. Select Monthly or Yearly billing
5. Use test card `4242 4242 4242 4242`
6. On success, the club's subscription_status should update to 'active'

#### Via Stripe CLI (quick smoke test)

```bash
stripe trigger checkout.session.completed
```

### Go-Live Checklist

- [ ] Replace sandbox restricted key with live secret key (sk_live_...)
- [ ] Replace test publishable key with live key (pk_live_...)
- [ ] Create live products/prices in Stripe Dashboard
- [ ] Update webhook endpoint URL to live mode
- [ ] Update STRIPE_WEBHOOK_SECRET with live signing secret
- [ ] Set CASHIER_WEBHOOK_URL to live endpoint
- [ ] Verify end-to-end with a real card ($1 test charge)
- [ ] Remove test/draft data from production DB
