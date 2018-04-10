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
        $model = $model->select(\DB::raw('facebook_profiles.id, facebook_profiles.facebook_id, facebook_profiles.type,
        facebook_profiles.name, facebook_profiles.username, facebook_profiles.fan_count, facebook_profiles.picture,
        facebook_profiles.cover,
        facebook_profiles.verification_status, post_count, facebook_profiles.share_count, facebook_profiles.interaction_count, 
        facebook_profiles.reaction_count, facebook_profiles.comment_count'))
        ->orderBy('facebook_profiles.fan_count', 'desc');
        return $model;
    }
}
