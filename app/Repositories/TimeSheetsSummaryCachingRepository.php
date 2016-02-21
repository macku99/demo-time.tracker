<?php namespace App\Repositories;

use App\DataModels\TimeSheetsSummary\TimeSheetsSummary;
use App\DataModels\TimeSheetsSummary\TimeSheetsSummaryRepository;
use Illuminate\Contracts\Cache\Repository as Cache;

/**
 * TimeSheetsSummary Caching Repository.
 *
 * @author  macku99
 * @version 1.0
 * @package Singularity
 */
class TimeSheetsSummaryCachingRepository implements TimeSheetsSummaryRepository
{

    /**
     * @var TimeSheetsSummaryRepository
     */
    protected $repository;

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @param TimeSheetsSummaryRepository $repository
     * @param Cache                       $cache
     */
    public function __construct(TimeSheetsSummaryRepository $repository, Cache $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * Total hours worked for a certain date by a given user.
     *
     * @param  int    $userId
     * @param  string $date
     * @return int
     */
    public function totalHoursWorkedByUserForDate($userId, $date)
    {
        return $this->cache->rememberForever("timesheets.summary.{$userId}.{$date}", function () use ($userId, $date) {
            return $this->repository->totalHoursWorkedByUserForDate($userId, $date);
        });
    }

}