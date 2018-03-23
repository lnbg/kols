<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class YoutubeVideo.
 *
 * @package namespace App\Entities;
 */
class YoutubeVideo extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'youtube_videos';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

}
