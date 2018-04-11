<?php

namespace App\Criteria\Facebook;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class GetFacebookTheBestTimePublishCriteria.
 *
 * @package namespace App\Criteria;
 */
class GetFacebookTheBestTimePublishCriteria implements CriteriaInterface
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
        return $model->select(\DB::raw("TIME_FORMAT(facebook_created_at, '%H:00') as time, count(id) as count"))
        ->where('profile_id', '=', $this->profileID)
        ->groupBy('time');
    }
}
