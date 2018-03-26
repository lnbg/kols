<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class InstagramProfile.
 *
 * @package namespace App\Entities;
 */
class InstagramProfile extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'instagram_profiles';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

}
