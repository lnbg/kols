<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class AnalyticsDistributionOfPagePostTypeCriteria.
 *
 * @package namespace App\Criteria;
 */
class AnalyticsDistributionOfPagePostTypeCriteria implements CriteriaInterface
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
        $model = $model->where('profile_id', $this->profileID)->select(\DB::raw('facebook_posts.type, sum(id) as count'))
        ->groupBy('facebook_posts.type')
        ->get();
        return $model;
    }
}
