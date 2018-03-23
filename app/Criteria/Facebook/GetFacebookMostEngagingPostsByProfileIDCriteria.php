<?php

namespace App\Criteria\Facebook;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class GetFacebookMostEngagingPostsByProfileIDCriteria.
 *
 * @package namespace App\Criteria;
 */
class GetFacebookMostEngagingPostsByProfileIDCriteria implements CriteriaInterface
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
        return $model->where('profile_id', $this->profileID)
        ->where('facebook_created_at', '>=', \DB::raw('DATE_SUB(NOW(), INTERVAL '. $this->lastDays .' DAY)'))
        ->select(\DB::raw('id, facebook_id, type, profile_id, link, message, name, caption, description, full_picture,
        like_count, haha_count, wow_count, sad_count, love_count, angry_count, thankful_count, comment_count, share_count,
        sum(like_count + haha_count + wow_count + sad_count + love_count + angry_count +
        thankful_count + comment_count + share_count) as interactions_count'))
        ->groupBy('id')
        ->orderBy('interactions_count', 'DESC');
    }
}
