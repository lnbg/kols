<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class FacebookPost.
 *
 * @package namespace App\Entities;
 */
class FacebookPost extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'facebook_posts';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

}
