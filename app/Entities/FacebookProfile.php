<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class FacebookProfile.
 *
 * @package namespace App\Entities;
 */
class FacebookProfile extends Model implements Transformable
{
    use TransformableTrait;


    protected $table = 'facebook_profiles';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    public function posts() 
    {
        return $this->hasMany('App\Entities\FacebookPost', 'profile_id');
    }

}
