<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Automatically scope model queries to the current user's club.
 *
 * Usage in a Model:
 *   use App\Models\Traits\WithClubScoping;
 *
 * On every query, a `where('club_id', $currentClubId)` is applied.
 * To bypass (e.g., admin showing all clubs), use:
 *   Model::withoutGlobalScope('club_scope')->get()
 */
trait WithClubScoping
{
    public static function bootWithClubScoping(): void
    {
        static::addGlobalScope('club_scope', function (Builder $builder) {
            $clubId = request()->input('_current_club_id');
            if ($clubId) {
                $builder->where($builder->getModel()->getTable() . '.club_id', $clubId);
            }
        });
    }
}
