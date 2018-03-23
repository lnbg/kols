<?php

namespace App\Criteria\Facebook;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class GetFacebookAnalyticsCriteria.
 *
 * @package namespace App\Criteria;
 */
class GetFacebookAnalyticsCriteria implements CriteriaInterface
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
        $model = $model->join('facebook_posts', 'facebook_profiles.id', 'facebook_posts.profile_id')
        ->select(\DB::raw('facebook_profiles.id, facebook_profiles.facebook_id, facebook_profiles.type,
        facebook_profiles.name, facebook_profiles.username, facebook_profiles.fan_count, facebook_profiles.picture,
        sum(like_count) AS sum_like_count, 
        sum(haha_count) as sum_haha_count,
        sum(wow_count) as sum_wow_count, 
        sum(love_count) as sum_love_count,
        sum(sad_count) as sum_sad_count, 
        sum(facebook_posts.id) as sum_posts, 
        sum(share_count) as sum_share_count, 
        sum(thankful_count) as sum_thankyou_count, 
        sum(comment_count) as sum_comment_count'))
        ->groupBy('profile_id');
        return $model;
    }
}
