<?php

namespace App\Services;

use App\Models\CourtBooking;
use App\Models\CourtTournamentRental;
use App\Support\BusinessTime;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CourtBookingUsageService
{
    public function topUsers(int $limit = 32): Collection
    {
        $asOfDate = Carbon::today(BusinessTime::TIMEZONE)->toDateString();
        $normalizeName = static function (string $name): string {
            $name = trim($name);
            $normalized = preg_replace('/\s+/u', ' ', $name);

            return mb_strtolower($normalized ?? $name, 'UTF-8');
        };

        $bookingGroups = CourtBooking::query()
            ->where('status', CourtBooking::STATUS_CONFIRMED)
            ->whereNotNull('team_name')
            ->whereRaw("TRIM(team_name) <> ''")
            ->whereDate('booking_date', '<=', $asOfDate)
            ->selectRaw('MIN(TRIM(team_name)) as team_name, COUNT(*) as booking_count, COALESCE(SUM(duration_minutes), 0) as booking_minutes')
            ->groupByRaw('LOWER(TRIM(team_name))')
            ->get();

        $tournamentRentalGroups = CourtTournamentRental::query()
            ->where('status', '!=', CourtTournamentRental::STATUS_CANCELLED)
            ->whereNotNull('tournament_name_snapshot')
            ->whereRaw("TRIM(tournament_name_snapshot) <> ''")
            ->whereDate('rental_date', '<=', $asOfDate)
            ->selectRaw('MIN(TRIM(tournament_name_snapshot)) as team_name, COUNT(*) as rental_count, COALESCE(SUM(duration_minutes), 0) as rental_minutes')
            ->groupByRaw('LOWER(TRIM(tournament_name_snapshot))')
            ->get();

        $totals = [];
        $ensureRow = static function (array &$rows, string $key, string $displayName): void {
            $rows[$key] ??= [
                'team_name' => trim($displayName),
                'booking_count' => 0,
                'rental_count' => 0,
                'total_minutes' => 0,
            ];

            if (strcasecmp($displayName, $rows[$key]['team_name']) < 0) {
                $rows[$key]['team_name'] = trim($displayName);
            }
        };

        foreach ($bookingGroups as $bookingGroup) {
            $displayName = trim((string) $bookingGroup->team_name);
            $key = $normalizeName($displayName);
            if ($key === '') {
                continue;
            }

            $ensureRow($totals, $key, $displayName);
            $totals[$key]['booking_count'] += (int) $bookingGroup->booking_count;
            $totals[$key]['total_minutes'] += (int) $bookingGroup->booking_minutes;
        }

        foreach ($tournamentRentalGroups as $rentalGroup) {
            $displayName = trim((string) $rentalGroup->team_name);
            $key = $normalizeName($displayName);
            if ($key === '') {
                continue;
            }

            $ensureRow($totals, $key, $displayName);
            $totals[$key]['rental_count'] += (int) $rentalGroup->rental_count;
            $totals[$key]['total_minutes'] += (int) $rentalGroup->rental_minutes;
        }

        return collect(array_values($totals))
            ->filter(fn (array $row) => $row['total_minutes'] > 0)
            ->sort(function (array $left, array $right): int {
                return ($right['total_minutes'] <=> $left['total_minutes'])
                    ?: strcasecmp($left['team_name'], $right['team_name']);
            })
            ->take(max(1, min($limit, 32)))
            ->values();
    }
}
