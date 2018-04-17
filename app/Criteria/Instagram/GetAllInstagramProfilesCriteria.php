<?php

namespace App\Criteria\Instagram;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class GetInstagramProfilesCriteria.
 *
 * @package namespace App\Criteria\Instagram;
 */
class GetAllInstagramProfilesCriteria implements CriteriaInterface
{
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
        $model = $model->select(\DB::raw('instagram_accounts.id, instagram_accounts.instagram_id, instagram_accounts.name,
        instagram_accounts.username, instagram_accounts.media_count, instagram_accounts.followed_by_count, instagram_accounts.follow_count,
        instagram_accounts.picture, interaction_count, like_count as sum_like_count, comment_count as sum_comment_count'))
        ->orderBy('like_count', 'desc');
        return $model;
    }
}
