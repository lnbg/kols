<?php

namespace App\Criteria\Instagram;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class GetInstagramMostEngagingByTagCriteria.
 *
 * @package namespace App\Criteria;
 */
class GetInstagramMediaByTagCriteria implements CriteriaInterface
{

    /**
     * tag
     *
     * @var [int]
     */
    private $tag;
    
    public function __construct($tag)
    {
        $this->tag = $tag;
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
        return $model->whereRaw(\DB::raw("tags like binary '%#" . $this->tag . "\"%'"))
        ->orderBy(\DB::raw('like_count + comment_count'), 'DESC');
    }
}
