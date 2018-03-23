<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\YoutubeVideoRepository;
use App\Entities\YoutubeVideo;
use App\Validators\YoutubeVideoValidator;

/**
 * Class YoutubeVideoRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class YoutubeVideoRepositoryEloquent extends BaseRepository implements YoutubeVideoRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return YoutubeVideo::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return YoutubeVideoValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
