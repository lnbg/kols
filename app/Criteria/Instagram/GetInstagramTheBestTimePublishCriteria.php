<?php

namespace App\Criteria\Instagram;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class GetInstagramTheBestTimePublishCriteria.
 *
 * @package namespace App\Criteria;
 */
class GetInstagramTheBestTimePublishCriteria implements CriteriaInterface
{
     /**
     * Profile ID
     *
     * @var [int]
     */
    private $profileID;

    public function __construct($profileID)
    {
        $this->profileID = $profileID;
    }
    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->select(\DB::raw("TIME_FORMAT(instagram_created_time, '%H:00') as time, count(id) as count"))
        ->where('account_id', '=', $this->profileID)
        ->groupBy('time');
    }
}
