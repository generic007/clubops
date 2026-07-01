<?php

namespace App\Enums;

enum TicketType: string
{
    case LedgerQuestion = 'ledger_question';
    case PromoIssue = 'promo_issue';
    case PlatformAccess = 'platform_access';
    case GameComplaint = 'game_complaint';
    case Collusion = 'collusion';
    case Behavior = 'behavior';
    case Disconnection = 'disconnection';
    case Other = 'other';
}
