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
        $model = $model->leftJoin('instagram_media', 'instagram_profiles.id', 'instagram_media.profile_id')
        ->select(\DB::raw('instagram_profiles.id, instagram_profiles.instagram_id, instagram_profiles.name,
        instagram_profiles.username, instagram_profiles.media_count, instagram_profiles.follower_count, instagram_profiles.following_count,
        instagram_profiles.picture,
        sum(instagram_media.like_count) AS sum_like_count, 
        sum(instagram_media.comment_count) as sum_comment_count'))
        ->groupBy('instagram_profiles.id');
        return $model;
    }
}
