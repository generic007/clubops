<?php

namespace App\Enums;

enum PromoType: string
{
    case WelcomeBonus = 'welcome_bonus';
    case ReferralBonus = 'referral_bonus';
    case Reactivation = 'reactivation';
    case VipOffer = 'vip_offer';
    case Tournament = 'tournament';
    case StartingIncentive = 'starting_incentive';
    case VolumeReward = 'volume_reward';
}
