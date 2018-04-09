<?php

namespace App\Criteria\Instagram;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class GetInstagramMostEngagingPostsByProfileIDCriteria.
 *
 * @package namespace App\Criteria\Instagram;
 */
class GetInstagramMostEngagingPostsByProfileIDCriteria implements CriteriaInterface
{
    /**
     * Profile ID
     *
     * @var [int]
     */
    private $profileID;
    /**
     * Last Days
     *
     * @var [int]
     */
    private $lastDays;

    public function __construct($profileID, $lastDays)
    {
        $this->profileID = $profileID;
        $this->lastDays = $lastDays;
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
        return $model->where('account_id', $this->profileID)
        ->where('instagram_created_time', '>=', \DB::raw('DATE_SUB(NOW(), INTERVAL '. $this->lastDays .' DAY)'))
        ->select(\DB::raw('id, instagram_id, instagram_media.type, account_id, link, caption, image_url, video_url, sidecar_media, filter, tags, like_count, comment_count, sum(like_count + comment_count) as interactions_count'))
        ->groupBy('id')
        ->orderBy('interactions_count', 'DESC')->limit(10);
    }
}
