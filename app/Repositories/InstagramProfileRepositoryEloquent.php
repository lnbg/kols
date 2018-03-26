<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\InstagramProfileRepository;
use App\Entities\InstagramProfile;
use App\Validators\InstagramProfileValidator;

/**
 * Class InstagramProfileRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class InstagramProfileRepositoryEloquent extends BaseRepository implements InstagramProfileRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return InstagramProfile::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return InstagramProfileValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
