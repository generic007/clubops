<?php

namespace App\Enums;

enum PlayerStatus: string
{
    case Lead = 'lead';
    case Pending = 'pending';
    case Active = 'active';
    case Inactive = 'inactive';
    case Vip = 'vip';
    case Suspended = 'suspended';
    case Banned = 'banned';
    case Excluded = 'excluded';
}
