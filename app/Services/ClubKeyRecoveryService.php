<?php

namespace App\Services;

use App\Models\Club;
use Illuminate\Support\Facades\Log;

/**
 * Key escrow for zero-trust password recovery.
 *
 * During setup, the club master key is encrypted TWO ways:
 * 1. With the owner's password (password_encrypted_club_key) — normal login path
 * 2. With a server-held key (server_encrypted_club_key) — password recovery path
 *
 * On password reset, we decrypt the escrowed blob with the server key,
 * then re-encrypt with the new password. The user never loses data.
 */
class ClubKeyRecoveryService
{
    /**
     * Get the server key used for key escrow.
     * Falls back to APP_KEY if no dedicated key is configured.
     */
    public static function serverKey(): string
    {
        $escrowKey = config('clubops.escrow_key');
        if (!empty($escrowKey)) {
            // Derive a 32-byte key from the configured escrow key
            return hash('sha256', $escrowKey, true);
        }

        // Fallback: use APP_KEY (less secure but functional)
        $appKey = config('app.key');
        if (empty($appKey)) {
            throw new \RuntimeException('No escrow key or APP_KEY configured for password recovery.');
        }
        // APP_KEY is base64-encoded — decode it for raw bytes
        $decoded = base64_decode(str_replace('base64:', '', $appKey), true);
        return $decoded ?: hash('sha256', $appKey, true);
    }

    /**
     * Encrypt the club key with the server escrow key.
     *
     * @param string $clubKey  32-byte club master key
     * @return string          base64-encoded ciphertext
     */
    public static function encryptForRecovery(string $clubKey): string
    {
        $key = self::serverKey();
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $ciphertext = sodium_crypto_secretbox($clubKey, $nonce, $key);

        return base64_encode($nonce . $ciphertext);
    }

    /**
     * Decrypt the club key from the escrowed blob.
     *
     * @param string $encrypted  base64-encoded ciphertext
     * @return string|null       32-byte club key, or null on failure
     */
    public static function decryptFromRecovery(string $encrypted): ?string
    {
        try {
            $key = self::serverKey();
            $decoded = base64_decode($encrypted, true);
            if ($decoded === false || strlen($decoded) < SODIUM_CRYPTO_SECRETBOX_NONCEBYTES) {
                return null;
            }

            $nonce = substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $ciphertext = substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $plaintext = sodium_crypto_secretbox_open($ciphertext, $nonce, $key);

            return $plaintext !== false ? $plaintext : null;
        } catch (\Throwable $e) {
            Log::error('ClubKeyRecovery: decrypt failed', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Re-encrypt the club key with a new password (during password reset).
     * Returns the new password-encrypted blob for storage.
     */
    public static function reEncryptWithNewPassword(
        string $serverEncryptedKey,
        string $newPassword
    ): ?string {
        $clubKey = self::decryptFromRecovery($serverEncryptedKey);
        if ($clubKey === null) {
            return null;
        }

        return ClubEncryptionService::encryptClubKey($clubKey, $newPassword);
    }

    /**
     * Check if a club has a recovery escrow key stored.
     */
    public static function hasRecoveryKey(Club $club): bool
    {
        return !empty($club->server_encrypted_club_key);
    }
}
