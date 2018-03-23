<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Entities\YoutubeChannel;

/**
 * Class YoutubeChannelTransformer.
 *
 * @package namespace App\Transformers;
 */
class YoutubeChannelTransformer extends TransformerAbstract
{
    /**
     * Transform the YoutubeChannel entity.
     *
     * @param \App\Entities\YoutubeChannel $model
     *
     * @return array
     */
    public function transform(YoutubeChannel $model)
    {
        return [
            'id'            => (int) $model->id,
            'youtube_id'    => (int) $model->youtube_id,
            'custom_url'    => (string) $model->custom_url,
            'title'         => (string) $model->title,
            'description'   => (string) $model->description,
            'country'       => (string) $model->country,
            'subscriber_count' => (int) $model->subscriber_count,
            'video_count'   => (int) $model->scubscriber_count,
            'view_count'    => (int) $model->view_count,
            'picture'       => (string) $model->picture,
            'uploads_playlist_id' => (int) $model->uploads_playlist_id,
            'published_at' => $model->published_at
        ];
    }
}
