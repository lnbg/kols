<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\InstagramMedia;

/**
 * Class InstagramMediaTransformer.
 *
 * @package namespace App\Transformers;
 */
class InstagramMediaTransformer extends TransformerAbstract
{
    /**
     * Transform the InstagramMedia entity.
     *
     * @param \App\Entities\InstagramMedia $model
     *
     * @return array
     */
    public function transform(InstagramMedia $model)
    {
        return [
            'id'         => (int) $model->id,
            'instagram_id' => (string) $model->instagram,
            'type' => (string) $model->type,
            'profile_id' => (int) $model->profile_id,
            'caption' => $model->caption,
            'media' => $model->media,
            'filter' => $model->filter,
            'tags' => $model->tags,
            'link' => $model->link,
            'like_count' => (int) $model->like_count,
            'comment_count' => (int) $model->comment_count,
        ];
    }
}
