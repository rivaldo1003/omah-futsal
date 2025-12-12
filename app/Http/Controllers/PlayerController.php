<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;
use Storage;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = 20;
        $search = $request->input('search');

        $query = Player::with('team')
            ->orderBy('name');

        // Search functionality only
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('jersey_number', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%")
                    ->orWhereHas('team', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $players = $query->paginate($perPage);

        return view('admin.players.index', compact('players'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teams = Team::where('status', 'active')->orderBy('name')->get();

        return view('admin.players.create', compact('teams'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'team_id' => 'nullable|exists:teams,id',
            'jersey_number' => 'nullable|integer|min:1|max:99',
            'position' => 'nullable|string|max:50',
            'goals' => 'integer|min:0',
            'assists' => 'integer|min:0',
            'yellow_cards' => 'integer|min:0',
            'red_cards' => 'integer|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('players', 'public');
            $validated['photo'] = $path;
        }

        Player::create($validated);

        return redirect()->route('admin.players.index')
            ->with('success', 'Player created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Player $player)
    {
        $player->load('team');

        return view('admin.players.show', compact('player'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Player $player)
    {
        $teams = Team::where('status', 'active')->orderBy('name')->get();

        return view('admin.players.edit', compact('player', 'teams'));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Player $player)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'jersey_number' => 'nullable|integer|min:1|max:99',
            'team_id' => 'nullable|exists:teams,id',
            'position' => 'nullable|string|max:50',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'goals' => 'nullable|integer|min:0',
            'assists' => 'nullable|integer|min:0',
            'yellow_cards' => 'nullable|integer|min:0',
            'red_cards' => 'nullable|integer|min:0',
        ]);

        try {
            // Handle photo upload/removal
            if ($request->has('remove_photo') && $request->remove_photo == '1') {
                // Remove current photo if exists
                if ($player->photo && Storage::disk('public')->exists($player->photo)) {
                    Storage::disk('public')->delete($player->photo);
                }
                $validated['photo'] = null;
            } elseif ($request->hasFile('photo')) {
                // Upload new photo
                if ($player->photo && Storage::disk('public')->exists($player->photo)) {
                    Storage::disk('public')->delete($player->photo);
                }
                $path = $request->file('photo')->store('players', 'public');
                $validated['photo'] = $path;
            } else {
                // Keep existing photo
                unset($validated['photo']);
            }

            $player->update($validated);

            return redirect()->route('admin.players.index')
                ->with('success', 'Player updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating player: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Player $player)
    {
        try {
            $player->delete();

            return redirect()->route('admin.players.index')
                ->with('success', 'Player deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting player: '.$e->getMessage());
        }
    }
}
