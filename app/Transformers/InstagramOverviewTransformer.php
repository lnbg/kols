<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\InstagramProfile;

/**
 * Class InstagramOverviewTransformer.
 *
 * @package namespace App\Transformers;
 */
class InstagramOverviewTransformer extends TransformerAbstract
{
    /**
     * Transform the InstagramProfile entity.
     *
     * @param \App\Entities\InstagramProfile $model
     *
     * @return array
     */
    public function transform(InstagramProfile $model)
    {
        return [
            'id'         => (int) $model->id,
            'instagram_id' => (int) $model->instagram_id,
            'name' => (string) $model->name,
            'username' => (string) $model->username,
            'media_count' => (int) $model->media_count,
            'followed_by_count' => (int) $model->followed_by_count,
            'follow_count' => (int) $model->follow_count,
            'picture' => (string) $model->picture,
            'sum_like_count' => (int) $model->sum_like_count,
            'sum_comment_count' => (int) $model->sum_comment_count,
            'like_count' => (int) $model->like_count,
            'interaction_count' => (int) $model->interaction_count,
            'comment_count' => (int) $model->comment_count,
        ];
    }

    /**
     * Transform array the InstagramProfile entity.
     *
     * @param Array
     *
     * @return array
     */
    public function transformArray($models)
    {
        $results = [];
        foreach ($models as $model) {
            $results[] = $this->transform($model);
        }
        return $results;
    }
}
