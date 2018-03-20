<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Repositories\FacebookProfileRepository;
use App\Repositories\FacebookPostRepository;
use App\Transformers\FacebookPostTransformer;
use App\Criteria\GetFacebookAnalyticsCriteria;
use App\Criteria\GetPostsFacebookByProfileIDCriteria;


/**
 * Class FacebookAnalyticsController.
 *
 * @package namespace App\Http\Controllers;
 */
class FacebookAnalyticsController extends BaseController
{
    /**
     * @var FacebookProfileRepository
     */
    protected $facebookProfileRepository;

    /**
     * @var FacebookProfileRepository
     */
    protected $facebookPostRepository;
    
    /**
     * FacebookProfilesController constructor.
     *
     * @param FacebookProfileRepository $repository
     */
    public function __construct(FacebookProfileRepository $facebookProfileRepository, FacebookPostRepository $facebookPostRepository)
    {
        $this->facebookProfileRepository = $facebookProfileRepository;
        $this->facebookPostRepository = $facebookPostRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getListFacebookAnalytics()
    {
        try {
            $this->facebookProfileRepository->pushCriteria(GetFacebookAnalyticsCriteria::class);
            $facebookAnalytics = $this->facebookProfileRepository->all();
            return $this->response()->array($facebookAnalytics);
        } catch (\Execption $e) {
            return $this->response()->errorInternal();
        }
    }

    public function getFacebookAnalyticsByProfileID(Request $request)
    {
        try {
            $profileID = $request->profile_id;
            $this->facebookPostRepository->pushCriteria(new GetPostsFacebookByProfileIDCriteria($profileID));
            $posts = $this->facebookPostRepository->paginate();
            return $this->response()->paginator($posts, new FacebookPostTransformer);
        } catch (\Exception $e) {
            return $this->response()->errorInternal();
        }
    }
}
