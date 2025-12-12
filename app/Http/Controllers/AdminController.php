<?php

namespace App\Http\Controllers;

use App\Models\MatchModel;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        // Middleware sederhana
        $this->middleware(function ($request, $next) {
            if (! session()->has('admin_logged_in')) {
                return redirect()->route('login');
            }

            return $next($request);
        })->except(['login', 'logout']);
    }

    public function login(Request $request)
    {
        // Login sederhana
        if ($request->password === 'admin123') {
            session(['admin_logged_in' => true]);

            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Password salah!');
    }

    public function logout()
    {
        session()->forget('admin_logged_in');

        return redirect('/');
    }

    public function dashboard()
    {
        $totalMatches = MatchModel::count();
        $pendingMatches = MatchModel::whereNull('home_score')->count();
        $totalTeams = Team::count();
        $totalPlayers = Player::count();

        return view('admin.dashboard', compact(
            'totalMatches',
            'pendingMatches',
            'totalTeams',
            'totalPlayers'
        ));
    }

    public function pertandingan()
    {
        $teams = Team::orderBy('name')->get();

        return view('admin.pertandingan', compact('teams'));
    }

    public function storePertandingan(Request $request)
    {
        $request->validate([
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'match_date' => 'required|date',
            'match_time' => 'required',
            'session' => 'required',
            'stage' => 'required|in:Group,Semifinal,Final,3rd Place',
        ]);

        MatchModel::create($request->all());

        return back()->with('success', 'Pertandingan berhasil dibuat!');
    }

    public function inputTim()
    {
        return view('admin.tim');
    }

    public function storeTim(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'group' => 'required|in:A,B',
        ]);

        Team::create($request->only('name', 'group'));

        return back()->with('success', 'Tim berhasil ditambahkan!');
    }

    public function inputPemain()
    {
        $teams = Team::orderBy('name')->get();

        return view('admin.pemain', compact('teams'));
    }

    public function storePemain(Request $request)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'name' => 'required|string|max:100',
            'jersey_number' => 'required|integer|min:1|max:99',
        ]);

        Player::create($request->all());

        return back()->with('success', 'Pemain berhasil ditambahkan!');
    }

    public function inputHasil($id)
    {
        $match = MatchModel::with(['homeTeam', 'awayTeam'])->findOrFail($id);

        return view('admin.hasil', compact('match'));
    }

    public function storeHasil(Request $request, $id)
    {
        $match = MatchModel::findOrFail($id);

        $request->validate([
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0',
        ]);

        // Update skor
        $match->update([
            'home_score' => $request->home_score,
            'away_score' => $request->away_score,
        ]);

        // Update statistik tim
        $this->updateTeamStats($match);

        return back()->with('success', 'Hasil pertandingan berhasil disimpan!');
    }

    private function updateTeamStats($match)
    {
        $homeTeam = Team::find($match->home_team_id);
        $awayTeam = Team::find($match->away_team_id);

        // Update home team
        $homeTeam->matches_played += 1;
        $homeTeam->goals_for += $match->home_score;
        $homeTeam->goals_against += $match->away_score;

        if ($match->home_score > $match->away_score) {
            $homeTeam->won += 1;
            $homeTeam->points += 3;
        } elseif ($match->home_score == $match->away_score) {
            $homeTeam->drawn += 1;
            $homeTeam->points += 1;
        } else {
            $homeTeam->lost += 1;
        }
        $homeTeam->save();

        // Update away team
        $awayTeam->matches_played += 1;
        $awayTeam->goals_for += $match->away_score;
        $awayTeam->goals_against += $match->home_score;

        if ($match->away_score > $match->home_score) {
            $awayTeam->won += 1;
            $awayTeam->points += 3;
        } elseif ($match->away_score == $match->home_score) {
            $awayTeam->drawn += 1;
            $awayTeam->points += 1;
        } else {
            $awayTeam->lost += 1;
        }
        $awayTeam->save();
    }

    public function inputStatPemain($match_id, $team_id)
    {
        $match = MatchModel::findOrFail($match_id);
        $team = Team::with('players')->findOrFail($team_id);

        return view('admin.stat-pemain', compact('match', 'team'));
    }

    public function storeStatPemain(Request $request, $match_id, $team_id)
    {
        $players = $request->input('players', []);

        foreach ($players as $player_id => $stats) {
            $player = Player::find($player_id);
            if ($player) {
                $player->update([
                    'goals' => $player->goals + ($stats['goals'] ?? 0),
                    'yellow_cards' => $player->yellow_cards + ($stats['yellow_cards'] ?? 0),
                    'red_cards' => $player->red_cards + ($stats['red_cards'] ?? 0),
                ]);
            }
        }

        return back()->with('success', 'Statistik pemain berhasil diperbarui!');
    }
}
