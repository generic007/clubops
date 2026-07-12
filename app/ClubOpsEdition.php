<?php

namespace App;

/**
 * Edition helpers for ClubOps Free vs ClubOps Pro.
 *
 * Free  = open-source, single-club, no player portal, no team management
 * Pro   = hosted, multi-club, team invites, player portal, encryption
 */
class ClubOpsEdition
{
    const EDITION = 'CLUBOPS_EDITION';

    public static function isPro(): bool
    {
        return env(self::EDITION, 'pro') === 'pro';
    }

    public static function isFree(): bool
    {
        return !static::isPro();
    }

    /**
     * Throw 404 if running the Free edition.
     * Call this at the top of Pro-only route handlers.
     */
    public static function requirePro(): void
    {
        if (static::isFree()) {
            abort(404);
        }
    }

    /**
     * Get the display name.
     */
    public static function label(): string
    {
        return static::isPro() ? 'ClubOps Pro' : 'ClubOps Free';
    }

    /**
     * Get the feature list for the current edition.
     */
    public static function features(): array
    {
        $common = [
            '📋 Player CRM with full history',
            '📒 Double-entry ledger',
            '🔄 Daily reconciliation',
            '🎁 Promotion tracking',
            '🎫 Dispute management',
            '📊 Reports & exports',
        ];

        if (static::isPro()) {
            return array_merge($common, [
                '👥 Team management with roles',
                '🔐 Zero-trust encryption (AES-256-GCM)',
                '🃏 Player portal (self-service)',
                '🌐 Multi-club hosting',
            ]);
        }

        return $common;
    }
}
