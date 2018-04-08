<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\InstagramProfile;

/**
 * Class InstagramProfileTransformer.
 *
 * @package namespace App\Transformers;
 */
class InstagramProfileTransformer extends TransformerAbstract
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
        ];
    }
}
