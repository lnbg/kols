<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Repositories\FacebookProfileRepository;
use App\Repositories\FacebookPostRepository;
use App\Transformers\FacebookPostTransformer;

use App\Criteria\GetFacebookAnalyticsCriteria;
use App\Criteria\GetPostsFacebookByProfileIDCriteria;
use App\Criteria\AnalyticsDistributionOfPagePostTypeCriteria;

use App\InfluxDB\InfluxDB;


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
     * @var InfluxDB
     */
    protected $influxDB;
    /**
     * FacebookProfilesController constructor.
     *
     * @param FacebookProfileRepository $repository
     */
    public function __construct(FacebookProfileRepository $facebookProfileRepository, 
    FacebookPostRepository $facebookPostRepository,
    influxDB $influxDB)
    {
        $this->facebookProfileRepository = $facebookProfileRepository;
        $this->facebookPostRepository = $facebookPostRepository;
        $this->influxDB = $influxDB;
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
    /**
     * get facebook analytics by profile id
     *
     * @param Request $request
     * @return \Illuminate\Http\Response 
     */
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
    /**
     * get Profile Fan from Influx Database split with day
     * @uses Influx database
     * @return \Illuminate\Http\Response
     */
    public function analyticsTotalPostsInDaysByProfileID($profile_id, Request $request)
    {
        try {
            $profileID = $profile_id;
            $lastDays = $request->last_days;
            $facebookID = $this->facebookProfileRepository->find($profileID)->facebook_id;
            $analyticsDatas = $this->influxDB->analyticsTotalPostInDaysByFacebookID($facebookID, $lastDays);
            return $this->response()->array($analyticsDatas);
        } catch (\Exception $ex) {
            return $this->response()->errorInternal();
        }
    }
    /**
     * analytics distribution of page post type by profile
     *
     * @param [int] $profile_id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function analyticsDistributionOfPagePostTypeByProfileID($profile_id, Request $request)
    {
        try {
            $profileID = $profile_id;
            $lastDays = $request->last_days;
            $this->facebookPostRepository->pushCriteria(new AnalyticsDistributionOfPagePostTypeCriteria($profileID));
            $analyticsDatas = $this->facebookPostRepository->all();
            return $this->response()->array($analyticsDatas);
        } catch (\Exception $ex) {
            return $this->response()->errorInternal();
        }
    }
     /**
     * influx db debugger
     * @uses Influx database
     * @return \Illuminate\Http\Response
     */
    public function debug()
    {
        // try {
            // $debugData = $this->influxDB->analyticsDistributionOfPagePostType(1410360992530635, 30);
            // return $this->response()->array($debugData);
            
        // } catch (\Exception $ex) {
        //     return $this->response()->errorInternal();
        // }
        $this->facebookPostRepository->pushCriteria(new AnalyticsDistributionOfPagePostTypeCriteria(1));
        $debugData = $this->facebookPostRepository->all();
        return $this->response()->array($debugData);
    }
}
