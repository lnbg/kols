<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\FacebookProfileRepository;
use App\Entities\FacebookProfile;
use App\Validators\FacebookProfileValidator;

/**
 * Class FacebookProfileRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class FacebookProfileRepositoryEloquent extends BaseRepository implements FacebookProfileRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return FacebookProfile::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {
        return FacebookProfileValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
