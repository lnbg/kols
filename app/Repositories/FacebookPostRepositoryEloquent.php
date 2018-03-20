<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\FacebookPostRepository;
use App\Entities\FacebookPost;
use App\Validators\FacebookPostValidator;

/**
 * Class FacebookPostRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class FacebookPostRepositoryEloquent extends BaseRepository implements FacebookPostRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return FacebookPost::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    public function validator()
    {

        return FacebookPostValidator::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
