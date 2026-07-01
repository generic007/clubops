<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\PlayerNote;
use App\Services\PlayerCrmService;
use Illuminate\Http\Request;

class PlayerNoteController extends Controller
{
    protected PlayerCrmService $crm;

    public function __construct(PlayerCrmService $crm)
    {
        $this->crm = $crm;
    }

    public function store(Request $request, Player $player)
    {
        $validated = $request->validate([
            'note' => 'required|string|max:5000',
            'category' => 'nullable|string|max:50',
        ]);

        $this->crm->addNote(
            $player,
            $request->user(),
            $validated['note'],
            $validated['category'] ?? 'general'
        );

        return back()->with('success', 'Note added.');
    }

    public function destroy(Player $player, PlayerNote $note)
    {
        if ($note->player_id !== $player->id) {
            abort(404);
        }

        $note->delete();

        return back()->with('success', 'Note deleted.');
    }
}
