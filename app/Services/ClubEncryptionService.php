<?php

namespace App\Services;

use App\Models\Club;
use Illuminate\Support\Facades\Log;

/**
 * Zero-trust, per-club encryption using AES-256-GCM via libsodium.
 *
 * - Each club gets a random 256-bit master key.
 * - The master key is encrypted with the owner's password-derived key.
 * - On login: agent's password derives the KEK, decrypts the club master key.
 * - All PII encrypted at the application layer — DB seizure yields only ciphertext.
 * - The club master key lives in the PHP session, never in the DB in plaintext.
 */
class ClubEncryptionService
{
    /**
     * Generate a new 256-bit club master key.
     */
    public static function generateClubKey(): string
    {
        return sodium_crypto_secretbox_keygen(); // 32 random bytes
    }

    /**
     * Encrypt the club master key with the owner's password.
     *
     * Returns a base64-encoded blob: salt (16) + nonce (24) + ciphertext.
     * Without the password, this blob cannot be decrypted.
     */
    public static function encryptClubKey(string $clubKey, string $password): string
    {
        $salt = random_bytes(SODIUM_CRYPTO_PWHASH_SALTBYTES);       // 16 bytes
        $kek  = self::deriveKey($password, $salt);                  // 32 bytes
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);  // 24 bytes
        $ciphertext = sodium_crypto_secretbox($clubKey, $nonce, $kek);

        // Ciphertext includes the Poly1305 MAC (16 extra bytes automatically)
        return base64_encode($salt . $nonce . $ciphertext);
    }

    /**
     * Decrypt the club master key from its encrypted blob.
     *
     * @return string|null  The club key (32 bytes), or null on failure.
     */
    public static function decryptClubKey(string $encryptedBlob, string $password): ?string
    {
        try {
            $decoded = base64_decode($encryptedBlob, true);
            if ($decoded === false || strlen($decoded) < 40) return null;

            $offset = 0;
            $salt   = substr($decoded, $offset, SODIUM_CRYPTO_PWHASH_SALTBYTES);
            $offset += SODIUM_CRYPTO_PWHASH_SALTBYTES;
            $nonce  = substr($decoded, $offset, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $offset += SODIUM_CRYPTO_SECRETBOX_NONCEBYTES;
            $ciphertext = substr($decoded, $offset);

            $kek = self::deriveKey($password, $salt);
            $key = sodium_crypto_secretbox_open($ciphertext, $nonce, $kek);

            return $key !== false ? $key : null;
        } catch (\Throwable $e) {
            Log::warning('ClubEncryption: failed to decrypt club key', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Derive a 256-bit key from a password + salt using Argon2id.
     * OPSLIMIT_MODERATE / MEMLIMIT_MODERATE — fast enough for login,
     * slow enough to make brute-force impractical.
     */
    private static function deriveKey(string $password, string $salt): string
    {
        return sodium_crypto_pwhash(
            32, // output length
            $password,
            $salt,
            SODIUM_CRYPTO_PWHASH_OPSLIMIT_MODERATE,
            SODIUM_CRYPTO_PWHASH_MEMLIMIT_MODERATE
        );
    }

    // ──────────────────────────────────────────────
    //  Per-value encryption / decryption (AES-256-GCM)
    // ──────────────────────────────────────────────

    /**
     * Encrypt a single value with the club master key.
     * Returns base64-encoded: nonce (12) + ciphertext + tag (16).
     */
    public static function encryptValue(string $plaintext, string $clubKey): string
    {
        $iv   = random_bytes(12); // GCM standard nonce size
        $tag  = '';
        $ciphertext = openssl_encrypt(
            $plaintext, 'aes-256-gcm', $clubKey,
            OPENSSL_RAW_DATA, $iv, $tag
        );
        return base64_encode($iv . $ciphertext . $tag);
    }

    /**
     * Decrypt a single value with the club master key.
     * Returns the plaintext, or null on auth failure (tampered data / wrong key).
     */
    public static function decryptValue(string $encrypted, string $clubKey): ?string
    {
        try {
            $decoded = base64_decode($encrypted, true);
            if ($decoded === false || strlen($decoded) < 29) return null;

            $iv         = substr($decoded, 0, 12);
            $tag        = substr($decoded, -16);
            $ciphertext = substr($decoded, 12, -16);

            $plaintext = openssl_decrypt(
                $ciphertext, 'aes-256-gcm', $clubKey,
                OPENSSL_RAW_DATA, $iv, $tag
            );
            return $plaintext !== false ? $plaintext : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    // ──────────────────────────────────────────────
    //  Session helpers
    // ──────────────────────────────────────────────

    /**
     * Store the decrypted club master key in the session.
     */
    public static function storeClubKeyInSession(string $clubKey, int $clubId): void
    {
        session()->put("clubops_encryption_key_{$clubId}", $clubKey);
    }

    /**
     * Retrieve the club master key from the session.
     */
    public static function getClubKeyFromSession(int $clubId): ?string
    {
        return session()->get("clubops_encryption_key_{$clubId}");
    }

    /**
     * Clear the club master key from session (on logout).
     */
    public static function clearClubKeyFromSession(int $clubId): void
    {
        session()->forget("clubops_encryption_key_{$clubId}");
    }

    /**
     * Check libsodium is available.
     */
    public static function isAvailable(): bool
    {
        return extension_loaded('sodium') && function_exists('openssl_encrypt');
    }
}
