<?php

namespace App\Services\Feed;

use App\Models\FeedItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FilterService
{
    /**
     * Apply filters to feed query
     *
     * @param Builder $query
     * @param array $criteria
     * @return Builder
     */
    public function applyFilters(Builder $query, array $criteria)
    {
        // Filter by content type
        if (!empty($criteria['types'])) {
            $query->whereIn('type', $criteria['types']);
        }

        // Filter by date range
        if (!empty($criteria['date_range'])) {
            $this->applyDateRangeFilter($query, $criteria['date_range']);
        }

        // Filter by author type
        if (!empty($criteria['author_types'])) {
            $this->applyAuthorTypeFilter($query, $criteria['author_types']);
        }

        // Filter by engagement level
        if (!empty($criteria['min_likes'])) {
            $query->where('likes_count', '>=', $criteria['min_likes']);
        }

        if (!empty($criteria['min_comments'])) {
            $query->where('comments_count', '>=', $criteria['min_comments']);
        }

        // Search in title or description
        if (!empty($criteria['search'])) {
            $search = $criteria['search'];
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        return $query;
    }

    /**
     * Apply date range filter
     *
     * @param Builder $query
     * @param string|array $dateRange
     */
    protected function applyDateRangeFilter(Builder $query, $dateRange)
    {
        if (is_string($dateRange)) {
            // Predefined ranges
            switch ($dateRange) {
                case 'today':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'week':
                    $query->where('created_at', '>=', Carbon::now()->subWeek());
                    break;
                case 'month':
                    $query->where('created_at', '>=', Carbon::now()->subMonth());
                    break;
                case '3months':
                    $query->where('created_at', '>=', Carbon::now()->subMonths(3));
                    break;
            }
        } elseif (is_array($dateRange) && isset($dateRange['from'], $dateRange['to'])) {
            // Custom range
            $query->whereBetween('created_at', [
                Carbon::parse($dateRange['from']),
                Carbon::parse($dateRange['to'])
            ]);
        }
    }

    /**
     * Apply author type filter
     *
     * @param Builder $query
     * @param array $authorTypes
     */
    protected function applyAuthorTypeFilter(Builder $query, array $authorTypes)
    {
        $query->whereHas('sourceable', function($q) use ($authorTypes) {
            if (in_array('verified', $authorTypes)) {
                $q->where('is_verified', true);
            }
            
            $userTypes = array_diff($authorTypes, ['verified']);
            if (!empty($userTypes)) {
                $q->whereIn('type', $userTypes);
            }
        });
    }

    /**
     * Save a filter preset
     *
     * @param string $name
     * @param array $criteria
     * @param bool $isDefault
     * @return \App\Models\FeedFilter
     */
    public function saveFilter($name, array $criteria, $isDefault = false)
    {
        $user = Auth::user();

        // If setting as default, unset other defaults
        if ($isDefault) {
            \App\Models\FeedFilter::where('user_id', $user->id)
                ->update(['is_default' => false]);
        }

        return \App\Models\FeedFilter::create([
            'user_id' => $user->id,
            'filter_name' => $name,
            'filter_criteria' => $criteria,
            'is_default' => $isDefault
        ]);
    }

    /**
     * Get user's saved filters
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserFilters()
    {
        return \App\Models\FeedFilter::where('user_id', Auth::id())
            ->orderBy('is_default', 'desc')
            ->orderBy('filter_name')
            ->get();
    }

    /**
     * Load a saved filter
     *
     * @param int $filterId
     * @return array|null
     */
    public function loadFilter($filterId)
    {
        $filter = \App\Models\FeedFilter::where('user_id', Auth::id())
            ->find($filterId);

        return $filter ? $filter->filter_criteria : null;
    }

    /**
     * Delete a saved filter
     *
     * @param int $filterId
     * @return bool
     */
    public function deleteFilter($filterId)
    {
        return \App\Models\FeedFilter::where('user_id', Auth::id())
            ->where('id', $filterId)
            ->delete();
    }

    /**
     * Get default filter for user
     *
     * @return array|null
     */
    public function getDefaultFilter()
    {
        $filter = \App\Models\FeedFilter::where('user_id', Auth::id())
            ->where('is_default', true)
            ->first();

        return $filter ? $filter->filter_criteria : null;
    }
}
