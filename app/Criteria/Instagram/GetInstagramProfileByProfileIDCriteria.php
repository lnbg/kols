<?php

namespace App\Criteria\Instagram;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class GetInstagramProfileByProfileIDCriteria.
 *
 * @package namespace App\Criteria\Instagram;
 */
class GetInstagramProfileByProfileIDCriteria implements CriteriaInterface
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
        return $model->where('id', $this->profileID);
    }
}
