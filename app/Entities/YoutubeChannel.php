<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class YoutubeChannel.
 *
 * @package namespace App\Entities;
 */
class YoutubeChannel extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'youtube_channels';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

}
