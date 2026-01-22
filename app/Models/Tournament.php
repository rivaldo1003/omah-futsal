<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tournament extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'start_date',
        'end_date',
        'location',
        'organizer',
        'type',
        'status',
        'groups_count',
        'teams_per_group',
        'qualify_per_group',
        'settings',
        'logo',
        'banner',
        'created_by',
        // Tambahan field untuk league
        'league_rounds',
        'league_standings_type',
        'league_allow_draw',
        // Tambahan field untuk knockout
        'knockout_format',
        'knockout_teams',
        'knockout_seeding',
        'knockout_byes',
        'knockout_third_place',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'settings' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'league_allow_draw' => 'boolean',
        'knockout_third_place' => 'boolean',
    ];

    protected $appends = [
        'duration',
        'formatted_dates',
        'teams_count',
        'matches_count',
        'match_duration',
        'half_time',
        'points_win',
        'points_draw',
        'points_loss',
        'max_substitutes',
        'type_label',
        'league_seeding',
        'league_match_order',
        'knockout_format_type',
        'total_matches',
    ];

    // Relasi dengan teams (many-to-many)
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_tournament')
            ->withPivot('group_name', 'seed')
            ->withTimestamps();
    }

    // Relasi dengan matches (one-to-many)
    public function matches(): HasMany
    {
        return $this->hasMany(Game::class, 'tournament_id');
    }

    // Relasi dengan creator
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accessor untuk label tipe turnamen
    public function getTypeLabelAttribute(): string
    {
        $types = [
            'league' => 'League',
            'knockout' => 'Knockout',
            'group_knockout' => 'Group + Knockout',
        ];

        return $types[$this->type] ?? $this->type;
    }

    // Accessors untuk match settings
    public function getMatchDurationAttribute()
    {
        return $this->settings['match_duration'] ?? 40;
    }

    public function getHalfTimeAttribute()
    {
        return $this->settings['half_time'] ?? 10;
    }

    public function getExtraTimeAttribute()
    {
        return $this->settings['extra_time'] ?? 10;
    }

    public function getPointsWinAttribute()
    {
        return $this->settings['points_win'] ?? 3;
    }

    public function getPointsDrawAttribute()
    {
        return $this->settings['points_draw'] ?? 1;
    }

    public function getPointsLossAttribute()
    {
        return $this->settings['points_loss'] ?? 0;
    }

    public function getMaxSubstitutesAttribute()
    {
        return $this->settings['max_substitutes'] ?? 5;
    }

    public function getMatchesPerDayAttribute()
    {
        return $this->settings['matches_per_day'] ?? 4;
    }

    public function getMatchIntervalAttribute()
    {
        return $this->settings['match_interval'] ?? 30;
    }

    public function getAllowDrawAttribute()
    {
        return $this->settings['allow_draw'] ?? true;
    }

    // Accessor untuk league settings
    public function getLeagueSeedingAttribute()
    {
        return $this->settings['league_seeding'] ?? 'random';
    }

    public function getLeagueMatchOrderAttribute()
    {
        return $this->settings['league_match_order'] ?? 'sequential';
    }

    public function getKnockoutFormatTypeAttribute()
    {
        return $this->knockout_format ?? 'single_elimination';
    }

    // Helper methods
    public function getTeamsCountAttribute(): int
    {
        return $this->teams()->count();
    }

    public function getMatchesCountAttribute(): int
    {
        return $this->matches()->count();
    }

    /**
     * Calculate total matches based on tournament type
     */
    public function getTotalMatchesAttribute(): int
    {
        $teamCount = $this->teams()->count();

        if ($teamCount < 2) {
            return 0;
        }

        switch ($this->type) {
            case 'league':
                $rounds = $this->league_rounds ?? 1;

                return ($teamCount * ($teamCount - 1) / 2) * $rounds;

            case 'knockout':
                $bracketSize = $this->knockout_teams ?? 8;
                $total = $bracketSize - 1;
                if ($this->knockout_third_place) {
                    $total += 1;
                }

                return $total;

            case 'group_knockout':
                $groupsCount = $this->groups_count ?? 2;
                $teamsPerGroup = $this->teams_per_group ?? 4;
                $qualifyPerGroup = $this->qualify_per_group ?? 2;

                // Group stage matches
                $groupMatches = $groupsCount * ($teamsPerGroup * ($teamsPerGroup - 1) / 2);

                // Knockout matches
                $knockoutTeams = $groupsCount * $qualifyPerGroup;
                $knockoutMatches = $knockoutTeams - 1;
                if ($this->knockout_third_place) {
                    $knockoutMatches += 1;
                }

                return $groupMatches + $knockoutMatches;

            default:
                return 0;
        }
    }

    /**
     * Calculate matches per group for group tournaments
     */
    public function getMatchesPerGroupAttribute(): int
    {
        if (in_array($this->type, ['group_knockout', 'league']) && $this->groups_count > 1) {
            $teamsPerGroup = $this->teams_per_group ?? 4;

            return $teamsPerGroup * ($teamsPerGroup - 1) / 2;
        }

        return 0;
    }

    /**
     * Get default round type based on tournament type
     */
    public function getDefaultRoundType(): string
    {
        switch ($this->type) {
            case 'league': return 'league';
            case 'knockout': return 'quarterfinal';
            case 'group_knockout': return 'group';
            default: return 'group';
        }
    }

    /**
     * Check if tournament has groups
     */
    public function hasGroups(): bool
    {
        return in_array($this->type, ['group_knockout']) && $this->groups_count > 1;
    }

    // Scopes
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming')
            ->where('start_date', '>=', now());
    }

    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Accessors
    public function getDurationAttribute(): int
    {
        if ($this->start_date && $this->end_date) {
            return now()->parse($this->start_date)->diffInDays($this->end_date) + 1;
        }

        return 0;
    }

    public function getFormattedDatesAttribute(): string
    {
        if ($this->start_date && $this->end_date) {
            $start = now()->parse($this->start_date)->format('d M Y');
            $end = now()->parse($this->end_date)->format('d M Y');

            return "{$start} - {$end}";
        }

        return 'Not scheduled';
    }

    // Get groups list
    public function getGroupsAttribute(): array
    {
        if (in_array($this->type, ['group_knockout', 'league']) && $this->groups_count) {
            $groups = [];
            for ($i = 0; $i < min($this->groups_count, 26); $i++) {
                $groups[] = chr(65 + $i); // A, B, C, ...
            }

            return $groups;
        }

        return [];
    }

    // Get teams by group
    public function getTeamsByGroup(string $group)
    {
        return $this->teams()->wherePivot('group_name', $group)->get();
    }

    // Get group standings
    public function getGroupStandings(string $group)
    {
        // Implementation depends on your standings logic
        return collect();
    }

    // Check if tournament is league type
    public function isLeague(): bool
    {
        return $this->type === 'league';
    }

    // Check if tournament is knockout type
    public function isKnockout(): bool
    {
        return $this->type === 'knockout';
    }

    // Check if tournament is group_knockout type
    public function isGroupKnockout(): bool
    {
        return $this->type === 'group_knockout';
    }

    /**
     * Get match time slots
     */
    public function getTimeSlotsAttribute(): array
    {
        $slots = $this->settings['match_time_slots'] ?? '14:00,16:00,18:00,20:00';

        return array_map('trim', explode(',', $slots));
    }

    /**
     * Check if tournament has knockout stage
     */
    public function hasKnockoutStage(): bool
    {
        return $this->type === 'knockout' || $this->type === 'group_knockout';
    }

    /**
     * Get knockout bracket size
     */
    public function getKnockoutBracketSizeAttribute(): int
    {
        if ($this->type === 'knockout') {
            return $this->knockout_teams ?? 8;
        } elseif ($this->type === 'group_knockout') {
            $groupsCount = $this->groups_count ?? 2;
            $qualifyPerGroup = $this->qualify_per_group ?? 2;

            return $groupsCount * $qualifyPerGroup;
        }

        return 0;
    }

    /**
     * Get tournament points system
     */
    public function getPointsSystemAttribute(): array
    {
        return [
            'win' => $this->points_win,
            'draw' => $this->points_draw,
            'loss' => $this->points_loss,
        ];
    }

    /**
     * Check if tournament allows draws
     */
    public function allowsDraws(): bool
    {
        if ($this->type === 'league') {
            return $this->league_allow_draw ?? true;
        }

        return $this->allow_draw ?? true;
    }

    /**
     * Get tournament configuration summary
     */
    public function getConfigurationSummaryAttribute(): array
    {
        $summary = [
            'type' => $this->type_label,
            'teams' => $this->teams_count,
            'duration' => $this->duration.' days',
            'total_matches' => $this->total_matches,
        ];

        switch ($this->type) {
            case 'league':
                $summary['rounds'] = $this->league_rounds ?? 1;
                $summary['allow_draws'] = $this->allowsDraws() ? 'Yes' : 'No';
                $summary['standings_type'] = $this->league_standings_type ?? 'total_points';
                break;

            case 'knockout':
                $summary['bracket_size'] = $this->knockout_teams ?? 8;
                $summary['format'] = $this->knockout_format_type;
                $summary['third_place'] = $this->knockout_third_place ? 'Yes' : 'No';
                break;

            case 'group_knockout':
                $summary['groups'] = $this->groups_count ?? 2;
                $summary['teams_per_group'] = $this->teams_per_group ?? 4;
                $summary['qualify_per_group'] = $this->qualify_per_group ?? 2;
                $summary['knockout_teams'] = $this->getKnockoutBracketSizeAttribute();
                break;
        }

        return $summary;
    }
}
