<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\YoutubeChannelRepository;
use App\Entities\YoutubeChannel;
use App\Validators\YoutubeChannelValidator;

/**
 * Class YoutubeChannelRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class YoutubeChannelRepositoryEloquent extends BaseRepository implements YoutubeChannelRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return YoutubeChannel::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return YoutubeChannelValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
