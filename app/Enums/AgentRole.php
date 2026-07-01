<?php

namespace App\Enums;

enum AgentRole: string
{
    case Owner = 'owner';
    case Manager = 'manager';
    case Agent = 'agent';
    case Support = 'support';
    case Accountant = 'accountant';
    case Auditor = 'auditor';
}
