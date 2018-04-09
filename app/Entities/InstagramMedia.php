<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class InstagramMedia.
 *
 * @package namespace App\Entities;
 */
class InstagramMedia extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'instagram_media';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    public function account()
    {
        return $this->belongsTo(\App\Entities\InstagramProfile::class, 'account_id');
    }
}
