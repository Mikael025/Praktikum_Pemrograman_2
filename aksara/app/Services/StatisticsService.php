<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk menangani statistics calculation
 * Centralized logic untuk dashboard dan laporan statistics
 * 
 * @package App\Services
 */
class StatisticsService
{
    /**
     * Calculate activity statistics (penelitian/pengabdian)
     * 
     * @param Builder $query Base query untuk model
     * @return array Statistics data dengan breakdown per status
     */
    public function calculateActivityStats(Builder $query): array
    {
        // Clone query untuk setiap count operation
        $total = (clone $query)->count();
        $diusulkan = (clone $query)->where('status', 'diusulkan')->count();
        $tidakLolos = (clone $query)->where('status', 'tidak_lolos')->count();
        
        // Lolos includes: lolos_perlu_revisi, lolos, revisi_pra_final
        $lolos = (clone $query)
            ->whereIn('status', ['lolos_perlu_revisi', 'lolos', 'revisi_pra_final'])
            ->count();
        
        $selesai = (clone $query)->where('status', 'selesai')->count();

        return [
            'total' => $total,
            'diusulkan' => $diusulkan,
            'tidak_lolos' => $tidakLolos,
            'lolos' => $lolos,
            'selesai' => $selesai,
            'sedang_proses' => $diusulkan + $lolos, // diusulkan + lolos variants
        ];
    }

    /**
     * Calculate statistics with caching
     * Useful untuk dashboard yang frequently accessed
     * 
     * @param string $cacheKey Cache key untuk statistics
     * @param Builder $query Base query
     * @param int $ttl Cache TTL in seconds (default: 1 hour)
     * @return array Statistics data
     */
    public function calculateActivityStatsWithCache(
        string $cacheKey,
        Builder $query,
        int $ttl = 3600
    ): array {
        return Cache::remember($cacheKey, $ttl, function () use ($query) {
            return $this->calculateActivityStats($query);
        });
    }

    /**
     * Calculate yearly statistics breakdown
     * 
     * @param Builder $query Base query
     * @param int|null $year Specific year (null = current year)
     * @return array Statistics per year
     */
    public function calculateYearlyStats(Builder $query, ?int $year = null): array
    {
        $targetYear = $year ?? now()->year;

        $yearlyQuery = (clone $query)->whereYear('created_at', $targetYear);

        return [
            'year' => $targetYear,
            'stats' => $this->calculateActivityStats($yearlyQuery),
        ];
    }

    /**
     * Calculate statistics by user (untuk dosen dashboard)
     * 
     * @param Builder $query Base query
     * @param int $userId User ID
     * @return array User-specific statistics
     */
    public function calculateUserStats(Builder $query, int $userId): array
    {
        $userQuery = (clone $query)->where('user_id', $userId);

        return $this->calculateActivityStats($userQuery);
    }

    /**
     * Calculate detailed breakdown dengan additional info
     * 
     * @param Builder $query Base query
     * @return array Detailed statistics
     */
    public function calculateDetailedStats(Builder $query): array
    {
        $basicStats = $this->calculateActivityStats($query);

        // Additional calculations
        $lolosPerluRevisi = (clone $query)->where('status', 'lolos_perlu_revisi')->count();
        $lolosLangsung = (clone $query)->where('status', 'lolos')->count();
        $revisiPraFinal = (clone $query)->where('status', 'revisi_pra_final')->count();

        // Calculate percentages
        $total = $basicStats['total'];
        $percentages = [];
        if ($total > 0) {
            foreach (['diusulkan', 'tidak_lolos', 'lolos', 'selesai'] as $status) {
                $percentages[$status] = round(($basicStats[$status] / $total) * 100, 1);
            }
        }

        return [
            'basic' => $basicStats,
            'detailed' => [
                'lolos_perlu_revisi' => $lolosPerluRevisi,
                'lolos_langsung' => $lolosLangsung,
                'revisi_pra_final' => $revisiPraFinal,
            ],
            'percentages' => $percentages,
        ];
    }

    /**
     * Get top users by activity count
     * 
     * @param string $modelClass Model class (Penelitian/Pengabdian)
     * @param int $limit Number of users to return
     * @return array Top users dengan activity count
     */
    public function getTopUsers(string $modelClass, int $limit = 10): array
    {
        try {
            $results = $modelClass::selectRaw('user_id, count(*) as total')
                ->with('user:id,name,nidn')
                ->groupBy('user_id')
                ->orderByDesc('total')
                ->limit($limit)
                ->get();

            return $results->map(function ($item) {
                return [
                    'user_id' => $item->user_id,
                    'user_name' => $item->user->name ?? 'Unknown',
                    'nidn' => $item->user->nidn ?? '-',
                    'total_activities' => $item->total,
                ];
            })->toArray();

        } catch (\Exception $e) {
            Log::error('Failed to get top users', [
                'model' => $modelClass,
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Calculate monthly trend untuk current year
     * 
     * @param Builder $query Base query
     * @return array Monthly data (Jan-Dec)
     */
    public function calculateMonthlyTrend(Builder $query): array
    {
        $currentYear = now()->year;
        $monthlyData = [];

        for ($month = 1; $month <= 12; $month++) {
            $count = (clone $query)
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $month)
                ->count();

            $monthlyData[] = [
                'month' => $month,
                'month_name' => date('F', mktime(0, 0, 0, $month, 1)),
                'count' => $count,
            ];
        }

        return [
            'year' => $currentYear,
            'monthly_data' => $monthlyData,
            'total' => array_sum(array_column($monthlyData, 'count')),
        ];
    }

    /**
     * Calculate success rate (lolos + selesai vs total)
     * 
     * @param Builder $query Base query
     * @return array Success rate data
     */
    public function calculateSuccessRate(Builder $query): array
    {
        $total = (clone $query)->count();
        
        if ($total === 0) {
            return [
                'total' => 0,
                'success_count' => 0,
                'success_rate' => 0,
                'rejection_count' => 0,
                'rejection_rate' => 0,
            ];
        }

        $successCount = (clone $query)
            ->whereIn('status', ['lolos_perlu_revisi', 'lolos', 'revisi_pra_final', 'selesai'])
            ->count();

        $rejectionCount = (clone $query)
            ->where('status', 'tidak_lolos')
            ->count();

        return [
            'total' => $total,
            'success_count' => $successCount,
            'success_rate' => round(($successCount / $total) * 100, 1),
            'rejection_count' => $rejectionCount,
            'rejection_rate' => round(($rejectionCount / $total) * 100, 1),
        ];
    }

    /**
     * Compare statistics between two periods
     * 
     * @param Builder $query Base query
     * @param string $period1Start Start date period 1
     * @param string $period1End End date period 1
     * @param string $period2Start Start date period 2
     * @param string $period2End End date period 2
     * @return array Comparison data
     */
    public function comparePeriodsStats(
        Builder $query,
        string $period1Start,
        string $period1End,
        string $period2Start,
        string $period2End
    ): array {
        $period1Query = (clone $query)
            ->whereBetween('created_at', [$period1Start, $period1End]);
        
        $period2Query = (clone $query)
            ->whereBetween('created_at', [$period2Start, $period2End]);

        $period1Stats = $this->calculateActivityStats($period1Query);
        $period2Stats = $this->calculateActivityStats($period2Query);

        // Calculate differences
        $differences = [];
        foreach ($period1Stats as $key => $value) {
            $diff = $period2Stats[$key] - $value;
            $percentChange = $value > 0 ? round(($diff / $value) * 100, 1) : 0;
            
            $differences[$key] = [
                'diff' => $diff,
                'percent_change' => $percentChange,
            ];
        }

        return [
            'period1' => [
                'range' => "{$period1Start} to {$period1End}",
                'stats' => $period1Stats,
            ],
            'period2' => [
                'range' => "{$period2Start} to {$period2End}",
                'stats' => $period2Stats,
            ],
            'differences' => $differences,
        ];
    }

    /**
     * Clear cached statistics
     * 
     * @param string|array $cacheKeys Cache key(s) to clear
     * @return bool Success status
     */
    public function clearStatsCache($cacheKeys): bool
    {
        try {
            if (is_array($cacheKeys)) {
                foreach ($cacheKeys as $key) {
                    Cache::forget($key);
                }
            } else {
                Cache::forget($cacheKeys);
            }

            Log::info('Statistics cache cleared', [
                'keys' => $cacheKeys,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to clear statistics cache', [
                'keys' => $cacheKeys,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Get common cache keys untuk statistics
     * 
     * @param int|null $userId Optional user ID for user-specific cache
     * @return array Array of cache keys
     */
    public function getStatsCacheKeys(?int $userId = null): array
    {
        $keys = [
            'admin.dashboard.penelitian.stats',
            'admin.dashboard.pengabdian.stats',
            'admin.dashboard.yearly.stats',
        ];

        if ($userId) {
            $keys[] = "dosen.dashboard.penelitian.stats.{$userId}";
            $keys[] = "dosen.dashboard.pengabdian.stats.{$userId}";
        }

        return $keys;
    }
}
