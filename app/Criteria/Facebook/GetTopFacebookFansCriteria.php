<?php

namespace App\Criteria\Facebook;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class GetTopFacebookFansCriteria.
 *
 * @package namespace App\Criteria\Facebook;
 */
class GetTopFacebookFansCriteria implements CriteriaInterface
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
        $now = date('Y-m-d');
        $affectiveDate = date('Y-m', strtotime($now . '-1 months'));
        $model = $model->leftJoin('facebook_posts', 'facebook_profiles.id', 'facebook_posts.profile_id')
        ->select(\DB::raw('facebook_profiles.id, facebook_profiles.facebook_id, facebook_profiles.type,
        facebook_profiles.name, facebook_profiles.username, facebook_profiles.fan_count, facebook_profiles.picture,
        facebook_profiles.cover,facebook_profiles.verification_status, 
        sum(facebook_posts.like_count + facebook_posts.haha_count + facebook_posts.sad_count + facebook_posts.wow_count 
        + facebook_posts.love_count + facebook_posts.thankful_count + 
        facebook_posts.angry_count + facebook_posts.comment_count + facebook_posts.share_count) as interaction_count'))
        ->whereRaw(\DB::raw('facebook_posts.created_at > ' . $affectiveDate))
        ->groupBy('facebook_profiles.id')->limit(10)
        ->orderBy('facebook_profiles.fan_count', 'desc');
        return $model;
    }
}
