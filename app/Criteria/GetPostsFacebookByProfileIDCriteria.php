<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class GetPostsFacebookByProfileIDCriteria.
 *
 * @package namespace App\Criteria;
 */
class GetPostsFacebookByProfileIDCriteria implements CriteriaInterface
{
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
        return $model->where('profile_id', $this->profileID);
    }
}
