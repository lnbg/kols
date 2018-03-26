<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\InstagramMediaRepository;
use App\Entities\InstagramMedia;
use App\Validators\InstagramMediaValidator;

/**
 * Class InstagramMediaRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class InstagramMediaRepositoryEloquent extends BaseRepository implements InstagramMediaRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return InstagramMedia::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return InstagramMediaValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
