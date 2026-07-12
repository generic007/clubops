<?php

namespace App\Models\Traits;

use App\Services\ClubEncryptionService;
use Illuminate\Database\Eloquent\Model;

/**
 * Auto-encrypt/decrypt specified model attributes using the per-club
 * encryption key stored in the session.
 *
 * Usage:
 *   class Player extends Model {
 *       use Encryptable;
 *       protected array $encryptable = ['name', 'email', 'phone', 'notes'];
 *   }
 *
 * The club_id must be available on the model or its relationships.
 * Encryption is transparent: set and get attributes normally.
 */
trait Encryptable
{
    /**
     * Boot the trait — register model event listeners.
     */
    public static function bootEncryptable(): void
    {
        // Encrypt before saving (both create and update)
        static::saving(function (Model $model) {
            $model->encryptEncryptableAttributes();
        });

        // Decrypt after retrieving from DB
        static::retrieved(function (Model $model) {
            $model->decryptEncryptableAttributes();
        });
    }

    /**
     * Encrypt all field values in $encryptable before persisting.
     */
    public function encryptEncryptableAttributes(): void
    {
        $clubKey = $this->resolveClubKey();
        if ($clubKey === null) return;

        foreach ($this->getEncryptableFields() as $field) {
            $plaintext = $this->attributes[$field] ?? null;
            if ($plaintext === null || $plaintext === '') continue;

            // Only encrypt if it's not already encrypted (starts with a base64-encoded blob)
            if ($this->isEncrypted($plaintext)) continue;

            $this->attributes[$field] = ClubEncryptionService::encryptValue((string) $plaintext, $clubKey);
        }
    }

    /**
     * Decrypt all encrypted field values after retrieving from DB.
     */
    public function decryptEncryptableAttributes(): void
    {
        $clubKey = $this->resolveClubKey();
        if ($clubKey === null) return;

        foreach ($this->getEncryptableFields() as $field) {
            $ciphertext = $this->attributes[$field] ?? null;
            if ($ciphertext === null || $ciphertext === '') continue;

            if (!$this->isEncrypted($ciphertext)) continue;

            $decrypted = ClubEncryptionService::decryptValue($ciphertext, $clubKey);
            if ($decrypted !== null) {
                $this->attributes[$field] = $decrypted;
            }
        }
    }

    /**
     * Manually decrypt a value (useful for search or export).
     */
    public function decryptField(string $field): ?string
    {
        $value = $this->attributes[$field] ?? null;
        if ($value === null || !$this->isEncrypted($value)) return $value;

        $clubKey = $this->resolveClubKey();
        if ($clubKey === null) return null;

        return ClubEncryptionService::decryptValue($value, $clubKey);
    }

    /**
     * Check if a value looks like an encrypted blob.
     */
    protected function isEncrypted(string $value): bool
    {
        // Encrypted blobs are base64, generally longer than 44 chars
        // and contain only base64 characters
        return strlen($value) > 44
            && preg_match('/^[A-Za-z0-9+\/=]+$/', $value)
            && !str_contains($value, ' ')
            && !str_contains($value, "\n");
    }

    /**
     * Get the list of encryptable fields for this model.
     */
    protected function getEncryptableFields(): array
    {
        return property_exists($this, 'encryptable') ? $this->encryptable : [];
    }

    /**
     * Resolve the club encryption key from:
     * 1. The model's own club_id attribute
     * 2. The model's club relationship
     * 3. The global request context
     */
    protected function resolveClubKey(): ?string
    {
        $clubId = $this->club_id
            ?? ($this->club?->id)
            ?? request()->input('_current_club_id');

        if ($clubId === null) return null;

        return ClubEncryptionService::getClubKeyFromSession((int) $clubId);
    }
}
