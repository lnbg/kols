<?php

namespace App\Criteria\Instagram;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class GetDistributionOfProfilePostTypeCriteria.
 *
 * @package namespace App\Criteria\Instagram;
 */
class GetDistributionOfProfilePostTypeCriteria implements CriteriaInterface
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
        return $model->where('account_id', $this->profileID)->select(\DB::raw('instagram_media.type, count(id) as count'))
        ->groupBy('instagram_media.type');
    }
}
