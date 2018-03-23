<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\YoutubeVideo;

/**
 * Class YoutubeVideoTransformer.
 *
 * @package namespace App\Transformers;
 */
class YoutubeVideoTransformer extends TransformerAbstract
{
    /**
     * Transform the YoutubeVideo entity.
     *
     * @param \App\Entities\YoutubeVideo $model
     *
     * @return array
     */
    public function transform(YoutubeVideo $model)
    {
        return [
            'id'            => (int) $model->id,
            'youtube_id'    => (int) $model->youtube_id,
            'video_id'      => (int) $model->video_id,
            'channel_id'    => (int) $model->channel_id,
            'title'         => (string) $model->title,
            'description'   => (string) $model->description,
            'thumbnail'     => (string) $model->thumbnail,
            'view_count'    => (int) $model->view_count,
            'like_count'    => (int) $model->like_count,
            'dislike_count' => (int) $model->dislike_count,
            'favorite_count' => (int) $model->favorite_count,
            'comment_count' => (int) $model->comment_count
        ];
    }
}
