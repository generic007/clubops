<?php

namespace App\Enums;

enum TransactionType: string
{
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
