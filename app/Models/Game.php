<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    protected $dates = ['match_date', 'youtube_uploaded_at'];
    
    protected $casts = [
        'match_date' => 'date:Y-m-d',
        'youtube_uploaded_at' => 'datetime',
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
        if (!$this->youtube_id) return null;
        return "https://www.youtube.com/embed/{$this->youtube_id}?rel=0&showinfo=0&modestbranding=1";
    }

    /**
     * Get YouTube watch URL
     */
    public function getYoutubeWatchUrlAttribute()
    {
        if (!$this->youtube_id) return null;
        return "https://www.youtube.com/watch?v={$this->youtube_id}";
    }

    /**
     * Get YouTube thumbnail URL dengan berbagai kualitas
     */
    public function getYoutubeThumbnailUrlAttribute()
    {
        if (!$this->youtube_id) return null;
        
        // Try different quality thumbnails
        $baseUrl = "https://img.youtube.com/vi/{$this->youtube_id}/";
        
        // Return maxresdefault if available, fallback to hqdefault
        return $baseUrl . "maxresdefault.jpg";
    }

    /**
     * Get thumbnail fallback URLs
     */
    public function getYoutubeThumbnailFallbackAttribute()
    {
        if (!$this->youtube_id) return null;
        
        $baseUrl = "https://img.youtube.com/vi/{$this->youtube_id}/";
        
        return [
            'maxres' => $baseUrl . "maxresdefault.jpg",
            'hq' => $baseUrl . "hqdefault.jpg",
            'mq' => $baseUrl . "mqdefault.jpg",
            'sd' => $baseUrl . "sddefault.jpg",
        ];
    }

    /**
     * Format YouTube duration
     */
    public function getYoutubeDurationFormattedAttribute()
    {
        if (!$this->youtube_duration) return null;
        
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
        if (!$this->youtube_uploaded_at) return null;
        return $this->youtube_uploaded_at->format('d M Y H:i');
    }

    /**
     * Get relative time (e.g., "2 days ago")
     */
    public function getHighlightUploadedAtRelativeAttribute()
    {
        if (!$this->youtube_uploaded_at) return null;
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
        if (empty($url)) return null;
        
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
        if (!$this->youtube_id) return null;
        
        $baseUrl = "https://img.youtube.com/vi/{$this->youtube_id}/";
        
        return [
            'maxres' => [
                'url' => $baseUrl . "maxresdefault.jpg",
                'width' => 1280,
                'height' => 720,
            ],
            'standard' => [
                'url' => $baseUrl . "sddefault.jpg",
                'width' => 640,
                'height' => 480,
            ],
            'high' => [
                'url' => $baseUrl . "hqdefault.jpg",
                'width' => 480,
                'height' => 360,
            ],
            'medium' => [
                'url' => $baseUrl . "mqdefault.jpg",
                'width' => 320,
                'height' => 180,
            ],
            'default' => [
                'url' => $baseUrl . "default.jpg",
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
        if ($this->status !== 'completed') return false;
        
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
        if ($this->status !== 'completed') return false;
        
        $homeGoals = $this->home_score ?? 0;
        $awayGoals = $this->away_score ?? 0;
        
        return $homeGoals == $awayGoals;
    }
}