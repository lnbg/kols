<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\FacebookPost;

/**
 * Class FacebookPostTransformer.
 *
 * @package namespace App\Transformers;
 */
class FacebookPostTransformer extends TransformerAbstract
{
    /**
     * Transform the FacebookPost entity.
     *
     * @param \App\Entities\FacebookPost $model
     *
     * @return array
     */
    public function transform(FacebookPost $model)
    {
        return [
            'id'         => (int) $model->id,
            'facebook_id' => (string) $model->facebook_id,
            'type' => (int) $model->type,
            'profile_id' => (int) $model->profile_id,
            'link' => (string) $model->link,
            'message' => (string) $model->message,
            'name' => (string) $model->name,
            'caption' => $model->caption,
            'description' => $model->description,
            'full_picture' => (string) $model->full_picture,
            'like_count' => (int) $model->like_count,
            'love_count' => (int) $model->love_count,
            'wow_count' => (int) $model->wow_count,
            'haha_count' => (int) $model->haha_count,
            'sad_count' => (int) $model->sad_count,
            'angry_count' => (int) $model->angry_count,
            'thankful_count' => (int) $model->thankful_count,
            'comment_count' => (int) $model->comment_count,
            'share_count' => (int) $model->share_count,
        ];
    }
}
