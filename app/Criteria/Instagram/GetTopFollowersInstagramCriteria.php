<?php

namespace App\Criteria\Instagram;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class GetTopFollowersInstagramCriteria.
 *
 * @package namespace App\Criteria;
 */
class GetTopFollowersInstagramCriteria implements CriteriaInterface
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
        $now = date('Y-m');
        return $model->leftJoin('instagram_media', 'instagram_accounts.id', 'instagram_media.account_id')
        ->select(\DB::raw('instagram_accounts.id, instagram_accounts.instagram_id, instagram_accounts.picture, 
        instagram_accounts.name, instagram_accounts.username, instagram_accounts.external_url, 
        sum(instagram_media.like_count + instagram_media.comment_count) as interaction_count'))
        ->groupBy('instagram_media.account_id')
        ->whereRaw(\DB::raw('instagram_media.created_at > ' . $now))
        ->orderBy('instagram_accounts.followed_by_count', 'desc')->limit(10);
    }
}
