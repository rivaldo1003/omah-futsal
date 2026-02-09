<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Game extends Model
{
    use HasFactory;

    protected $table = 'matches';

    protected $fillable = [
        'tournament_id',
        'match_date',
        'time_start',
        'time_end',
        'team_home_id',
        'team_away_id',
        'home_score',
        'away_score',
        'venue',
        'status',
        'round_type',
        'group_name',
        'notes',
        // YouTube fields only
        'youtube_id',
        'youtube_thumbnail',
        'youtube_duration',
        'youtube_uploaded_at',
        'is_penalty',
        'penalty_score',
        'et_score',
    ];

    protected $dates = ['match_date', 'youtube_uploaded_at'];

    protected $casts = [
        'match_date' => 'date:Y-m-d',
        'youtube_uploaded_at' => 'datetime',
        'is_penalty' => 'boolean',
    ];

    protected $appends = [
        'youtube_embed_url',
        'youtube_thumbnail_url',
        'youtube_duration_formatted',
        'has_highlight',
        'video_type',
        'display_thumbnail_url',
        'display_video_url',
        'highlight_uploaded_at_formatted',
    ];


    /**
     * Get display score with penalty info if exists
     */
    public function getDisplayScoreAttribute()
    {
        if ($this->status !== 'completed') {
            return 'VS';
        }

        $score = "{$this->home_score} - {$this->away_score}";

        // Jika ada extra time score
        if ($this->et_score) {
            list($etHome, $etAway) = explode('-', $this->et_score);
            $score .= " ({$etHome}-{$etAway} ET)";
        }

        // Jika selesai dengan penalti
        if ($this->is_penalty && $this->penalty_score) {
            $score .= " (Pen. {$this->penalty_score})";
        }

        return $score;
    }

    /**
     * Parse penalty score
     */
    public function getPenaltyScoreArrayAttribute()
    {
        if (!$this->penalty_score) {
            return null;
        }

        list($home, $away) = explode('-', $this->penalty_score);
        return [
            'home' => (int) $home,
            'away' => (int) $away,
        ];
    }

    // Di dalam class Game model

    /**
     * Get extras information for knockout matches
     */
    public function getExtrasInfoAttribute()
    {
        if ($this->status !== 'completed') {
            return null;
        }

        $extras = [];

        // Extra time score
        if ($this->et_score) {
            $extras['et_score'] = $this->et_score;
        }

        // Penalty information
        if ($this->is_penalty && $this->penalty_score) {
            $extras['is_penalty'] = true;
            $extras['penalty_score'] = $this->penalty_score;
        }

        return !empty($extras) ? $extras : null;
    }

    /**
     * Get winner with penalty consideration
     */
    public function getWinnerInfo()
    {
        if ($this->status !== 'completed') {
            return null;
        }

        $winner = null;
        $scoreType = 'regular';

        // Jika ada penalty
        if ($this->is_penalty && $this->penalty_score) {
            $penalty = $this->penalty_score_array;
            if ($penalty['home'] > $penalty['away']) {
                $winner = 'home';
            } else {
                $winner = 'away';
            }
            $scoreType = 'penalty';
        }
        // Jika ada extra time
        elseif ($this->et_score) {
            $et = $this->et_score_array;
            if ($et['home'] > $et['away']) {
                $winner = 'home';
            } else {
                $winner = 'away';
            }
            $scoreType = 'extra_time';
        }
        // Regular time
        else {
            if ($this->home_score > $this->away_score) {
                $winner = 'home';
            } elseif ($this->home_score < $this->away_score) {
                $winner = 'away';
            } else {
                $winner = 'draw';
            }
        }

        return [
            'winner' => $winner,
            'score_type' => $scoreType,
            'extras' => $this->extras_info
        ];
    }

    /**
     * Parse extra time score
     */
    public function getEtScoreArrayAttribute()
    {
        if (!$this->et_score) {
            return null;
        }

        list($home, $away) = explode('-', $this->et_score);
        return [
            'home' => (int) $home,
            'away' => (int) $away,
        ];
    }

    /**
     * Determine winner for knockout matches
     */
    public function getWinnerId()
    {
        if ($this->status !== 'completed') {
            return null;
        }

        // Jika ada penalti
        if ($this->is_penalty && $this->penalty_score) {
            $penalty = $this->penalty_score_array;
            return $penalty['home'] > $penalty['away']
                ? $this->team_home_id
                : $this->team_away_id;
        }

        // Jika ada extra time
        if ($this->et_score) {
            $et = $this->et_score_array;
            return $et['home'] > $et['away']
                ? $this->team_home_id
                : $this->team_away_id;
        }

        // Regular time
        return $this->home_score > $this->away_score
            ? $this->team_home_id
            : $this->team_away_id;
    }

    /**
     * Determine loser for knockout matches
     */
    public function getLoserId()
    {
        if ($this->status !== 'completed') {
            return null;
        }

        $winnerId = $this->getWinnerId();

        if ($winnerId === $this->team_home_id) {
            return $this->team_away_id;
        }

        return $this->team_home_id;
    }


    // ========== TAMBAHKAN INI ==========

    /**
     * Boot method untuk menangani events
     */
    protected static function boot()
    {
        parent::boot();

        // Ketika match akan dihapus, revert semua statistik pemain
        static::deleting(function ($game) {
            $game->revertAllPlayerStats();
        });
    }

    /**
     * Revert semua statistik pemain sebelum match dihapus
     */
    public function revertAllPlayerStats()
    {
        // Mulai transaction untuk konsistensi data
        DB::beginTransaction();

        try {
            Log::info('Starting to revert player stats for match', [
                'match_id' => $this->id,
                'match_title' => $this->getMatchTitleAttribute(),
                'home_score' => $this->home_score,
                'away_score' => $this->away_score
            ]);

            // Ambil semua events dari match ini
            $events = $this->events()->with(['player', 'relatedPlayer'])->get();

            Log::info('Found events to revert', [
                'match_id' => $this->id,
                'event_count' => $events->count()
            ]);

            foreach ($events as $event) {
                $this->revertSingleEventStats($event);
            }

            DB::commit();

            Log::info('Successfully reverted all player stats for match', [
                'match_id' => $this->id,
                'event_count' => $events->count()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error reverting player stats for match', [
                'match_id' => $this->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Revert statistik untuk single event
     */
    private function revertSingleEventStats(MatchEvent $event)
    {
        $player = $event->player;

        if (!$player) {
            Log::warning('Player not found for event', [
                'event_id' => $event->id,
                'player_id' => $event->player_id
            ]);
            return;
        }

        switch ($event->event_type) {
            case 'goal':
                // Kurangi goals dari player
                $player->decrement('goals');

                // Jika penalty goal, kurangi juga
                if ($event->is_penalty) {
                    $player->decrement('penalty_goals');
                }
                break;

            case 'yellow_card':
                $player->decrement('yellow_cards');
                break;

            case 'red_card':
                $player->decrement('red_cards');
                break;

            case 'penalty':
                $player->decrement('penalty_missed');
                break;
        }

        // Revert assist jika ada (untuk goal events)
        if ($event->related_player_id && $event->event_type === 'goal') {
            $relatedPlayer = \App\Models\Player::find($event->related_player_id);
            if ($relatedPlayer) {
                $relatedPlayer->decrement('assists');
            }
        }

        \Illuminate\Support\Facades\Log::info('Reverted stats for event', [
            'event_id' => $event->id,
            'event_type' => $event->event_type,
            'player_id' => $player->id,
            'player_name' => $player->name
        ]);
    }

    // ========== RELATIONSHIPS ==========
    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'team_home_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'team_away_id');
    }

    public function events()
    {
        return $this->hasMany(MatchEvent::class, 'match_id');
    }

    // ========== YOUTUBE METHODS ==========

    /**
     * Get YouTube embed URL
     */
    public function getYoutubeEmbedUrlAttribute()
    {
        if (!$this->youtube_id) {
            return null;
        }

        return "https://www.youtube.com/embed/{$this->youtube_id}?rel=0&showinfo=0&modestbranding=1";
    }

    /**
     * Get YouTube watch URL
     */
    public function getYoutubeWatchUrlAttribute()
    {
        if (!$this->youtube_id) {
            return null;
        }

        return "https://www.youtube.com/watch?v={$this->youtube_id}";
    }

    /**
     * Get YouTube thumbnail URL dengan berbagai kualitas
     */
    public function getYoutubeThumbnailUrlAttribute()
    {
        if (!$this->youtube_id) {
            return null;
        }

        // Try different quality thumbnails
        $baseUrl = "https://img.youtube.com/vi/{$this->youtube_id}/";

        // Return maxresdefault if available, fallback to hqdefault
        return $baseUrl . 'maxresdefault.jpg';
    }

    /**
     * Get thumbnail fallback URLs
     */
    public function getYoutubeThumbnailFallbackAttribute()
    {
        if (!$this->youtube_id) {
            return null;
        }

        $baseUrl = "https://img.youtube.com/vi/{$this->youtube_id}/";

        return [
            'maxres' => $baseUrl . 'maxresdefault.jpg',
            'hq' => $baseUrl . 'hqdefault.jpg',
            'mq' => $baseUrl . 'mqdefault.jpg',
            'sd' => $baseUrl . 'sddefault.jpg',
        ];
    }

    /**
     * Format YouTube duration
     */
    public function getYoutubeDurationFormattedAttribute()
    {
        if (!$this->youtube_duration) {
            return null;
        }

        $hours = floor($this->youtube_duration / 3600);
        $minutes = floor(($this->youtube_duration % 3600) / 60);
        $seconds = $this->youtube_duration % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    /**
     * Get formatted uploaded time
     */
    public function getHighlightUploadedAtFormattedAttribute()
    {
        if (!$this->youtube_uploaded_at) {
            return null;
        }

        return $this->youtube_uploaded_at->format('d M Y H:i');
    }

    /**
     * Get relative time (e.g., "2 days ago")
     */
    public function getHighlightUploadedAtRelativeAttribute()
    {
        if (!$this->youtube_uploaded_at) {
            return null;
        }

        return $this->youtube_uploaded_at->diffForHumans();
    }

    /**
     * Determine video type
     */
    public function getVideoTypeAttribute()
    {
        return $this->youtube_id ? 'youtube' : 'none';
    }

    /**
     * Get display thumbnail URL
     */
    public function getDisplayThumbnailUrlAttribute()
    {
        if ($this->youtube_id) {
            return $this->youtube_thumbnail_url;
        }

        // Default thumbnail berdasarkan match
        $homeName = $this->homeTeam ? $this->homeTeam->name : 'Home';
        $awayName = $this->awayTeam ? $this->awayTeam->name : 'Away';
        $homeInitial = strtoupper(substr($homeName, 0, 1));
        $awayInitial = strtoupper(substr($awayName, 0, 1));

        return "https://ui-avatars.com/api/?name={$homeInitial}+{$awayInitial}&background=1e3a8a&color=fff&size=600&bold=true&font-size=0.5";
    }

    /**
     * Get display video URL
     */
    public function getDisplayVideoUrlAttribute()
    {
        return $this->youtube_embed_url;
    }

    /**
     * Check if highlight exists
     */
    public function getHasHighlightAttribute()
    {
        return !empty($this->youtube_id);
    }

    /**
     * Parse YouTube URL to get video ID
     */
    public static function parseYoutubeId($url)
    {
        if (empty($url)) {
            return null;
        }

        // Remove any query parameters after ? or &
        $url = preg_replace('/\?.*$/', '', $url);

        $patterns = [
            // youtube.com/watch?v=XXX
            '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/',
            // youtu.be/XXX
            '/youtu\.be\/([a-zA-Z0-9_-]{11})/',
            // youtube.com/embed/XXX
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/',
            // youtube.com/v/XXX
            '/youtube\.com\/v\/([a-zA-Z0-9_-]{11})/',
            // youtube.com/shorts/XXX
            '/youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Validate YouTube URL format
     */
    public static function isValidYoutubeUrl($url)
    {
        $patterns = [
            '/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get all YouTube thumbnails with sizes
     */
    public function getYoutubeThumbnailsAttribute()
    {
        if (!$this->youtube_id) {
            return null;
        }

        $baseUrl = "https://img.youtube.com/vi/{$this->youtube_id}/";

        return [
            'maxres' => [
                'url' => $baseUrl . 'maxresdefault.jpg',
                'width' => 1280,
                'height' => 720,
            ],
            'standard' => [
                'url' => $baseUrl . 'sddefault.jpg',
                'width' => 640,
                'height' => 480,
            ],
            'high' => [
                'url' => $baseUrl . 'hqdefault.jpg',
                'width' => 480,
                'height' => 360,
            ],
            'medium' => [
                'url' => $baseUrl . 'mqdefault.jpg',
                'width' => 320,
                'height' => 180,
            ],
            'default' => [
                'url' => $baseUrl . 'default.jpg',
                'width' => 120,
                'height' => 90,
            ],
        ];
    }

    // ========== SCOPES ==========

    /**
     * Scope for matches with YouTube highlights
     */
    public function scopeWithHighlights($query)
    {
        return $query->whereNotNull('youtube_id');
    }

    /**
     * Scope for matches without highlights
     */
    public function scopeWithoutHighlights($query)
    {
        return $query->whereNull('youtube_id');
    }

    /**
     * Scope for recent highlights
     */
    public function scopeRecentHighlights($query, $limit = 5)
    {
        return $query->whereNotNull('youtube_id')
            ->orderBy('youtube_uploaded_at', 'desc')
            ->limit($limit);
    }

    // ========== EXISTING METHODS (Tetap Ada) ==========

    /**
     * Get match time range
     */
    public function getTimeRangeAttribute()
    {
        return date('H:i', strtotime($this->time_start)) . ' - ' .
            date('H:i', strtotime($this->time_end));
    }

    /**
     * Get match result
     */
    public function getResultAttribute()
    {
        if ($this->status !== 'completed') {
            return 'VS';
        }

        return "{$this->home_score} - {$this->away_score}";
    }

    /**
     * Scope for upcoming matches
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming')
            ->orderBy('match_date')
            ->orderBy('time_start');
    }

    /**
     * Scope for completed matches
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed')
            ->orderBy('match_date', 'desc');
    }

    /**
     * Scope by group
     */
    public function scopeByGroup($query, $group)
    {
        return $query->where('group_name', $group);
    }

    /**
     * Scope by tournament
     */
    public function scopeByTournament($query, $tournamentId)
    {
        return $query->where('tournament_id', $tournamentId);
    }

    /**
     * Get match status badge color
     */
    public function getStatusBadgeAttribute()
    {
        $colors = [
            'upcoming' => 'bg-warning text-dark',
            'ongoing' => 'bg-danger',
            'completed' => 'bg-success',
            'postponed' => 'bg-secondary',
        ];

        return $colors[$this->status] ?? 'bg-secondary';
    }

    /**
     * Check if match is live
     */
    public function getIsLiveAttribute()
    {
        return $this->status === 'ongoing';
    }

    /**
     * Get match title
     */
    public function getMatchTitleAttribute()
    {
        $home = $this->homeTeam ? $this->homeTeam->name : 'TBA';
        $away = $this->awayTeam ? $this->awayTeam->name : 'TBA';

        return "{$home} vs {$away}";
    }

    /**
     * Get match short info
     */
    public function getMatchShortInfoAttribute()
    {
        $date = $this->match_date->format('d M');
        $time = date('H:i', strtotime($this->time_start));

        return "{$date} • {$time} • {$this->venue}";
    }

    /**
     * Get goals count for a team
     */
    public function getTeamGoals($teamId)
    {
        if ($teamId == $this->team_home_id) {
            return $this->home_score ?? 0;
        } elseif ($teamId == $this->team_away_id) {
            return $this->away_score ?? 0;
        }

        return 0;
    }

    /**
     * Check if team won
     */
    public function didTeamWin($teamId)
    {
        if ($this->status !== 'completed') {
            return false;
        }

        $homeGoals = $this->home_score ?? 0;
        $awayGoals = $this->away_score ?? 0;

        if ($teamId == $this->team_home_id) {
            return $homeGoals > $awayGoals;
        } elseif ($teamId == $this->team_away_id) {
            return $awayGoals > $homeGoals;
        }

        return false;
    }

    /**
     * Check if match was a draw
     */
    public function getIsDrawAttribute()
    {
        if ($this->status !== 'completed') {
            return false;
        }

        $homeGoals = $this->home_score ?? 0;
        $awayGoals = $this->away_score ?? 0;

        return $homeGoals == $awayGoals;
    }
}
