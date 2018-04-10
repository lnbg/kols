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
        $model = $model->leftJoin('facebook_posts', 'facebook_profiles.id', 'facebook_posts.profile_id')
        ->select(\DB::raw('facebook_profiles.id, facebook_profiles.facebook_id, facebook_profiles.type,
        facebook_profiles.name, facebook_profiles.username, facebook_profiles.fan_count, facebook_profiles.picture,
        facebook_profiles.verification_status,post_count, share_count, interaction_count, reaction_count, comment_count'))
        //sum(like_count + haha_count + wow_count + love_count + sad_count + thankful_count + share_count + comment_count) as interaction_count'))
        ->groupBy('facebook_profiles.id')
        ->orderBy('facebook_profiles.fan_count', 'desc');
        return $model;
    }
}
