<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\FacebookProfile;

/**
 * Class FacebookAnalyticsOverviewTransformer.
 *
 * @package namespace App\Transformers;
 */
class FacebookAnalyticsOverviewTransformer extends TransformerAbstract
{
    /**
     * Transform the FacebookProfile entity.
     *
     * @param \App\Entities\FacebookProfile $model
     *
     * @return array
     */
    public function transform(FacebookProfile $model)
    {
        return [
            'id'         => (int) $model->id,
            /* place your other model properties here */
            'facebook_id' => (string) $model->facebook_id,
            'type' => (int) $model->type,
            'name' => (string) $model->name,
            'username' => (string) $model->username,
            'fan_count' => (int) $model->fan_count,
            'verification_status' => (string) $model->verification_status,
            'picture' => (string) $model->picture,
            'sum_haha_count' => (int) $model->sum_haha_count,
            'sum_wow_count' => (int) $model->sum_wow_count,
            'sum_love_count' => (int) $model->sum_love_count,
            'sum_sad_count' => (int) $model->sum_sad_count,
            'sum_posts' => (int) $model->sum_posts,
            'sum_share_count' => (int) $model->sum_share_count,
            'sum_thankyou_count' => (int) $model->sum_thankyou_count,
            'sum_comment_count' => (int) $model->sum_comment_count,
            'reaction_count' => (int) $model->reaction_count,
            'interaction_count' => (int) $model->interaction_count
        ];
    }
}
