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
 * 
 * @SWG\Swagger(
 *     basePath="/v1",
 *     host="api.kols-ammedia.local",
 *     schemes={"http"},
 *     @SWG\Info(
 *         version="1.0",
 *         title="Kols API",
 *         @SWG\Contact(name="Ken"),
 *     )
 * )
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
     * Facebook Overview API
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     path="/facebook-analytics",
     *     description="get facebook data for table overview",
     *     operationId="getListFacebookAnalytics",
     *     produces={"application/json"},
     *     tags={"facebook analytics"},
     *     @SWG\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @SWG\Response(
     *         response=500,
     *         description="Internal Error",
     *     )
     * )
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
    *  Facebook Posts API
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     path="/facebook-analytics/{profile_id}",
    *     description="get pagination facebook post via profile id",
    *     operationId="getFacebookAnalyticsByProfileID",
    *     produces={"application/json"},
    *     tags={"facebook analytics"},
    *     @SWG\Parameter(
    *       name="profile_id",
    *       in="path",
    *       description="profile id",
    *       required=true,
    *       type="integer"
    *     ),
    *     @SWG\Response(
    *         response=200,
    *         description="Successful operation"
    *     ),
    *     @SWG\Response(
    *         response=500,
    *         description="Internal Error",
    *     )
    * )
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
    *  Content Overview: Number of Page Posts
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     path="/facebook-analytics/{profile_id}/post?last_days={last_days}",
    *     description="analytics total posts in days by profile_id and number of days ago",
    *     operationId="getFacebookAnalyticsByProfileID",
    *     produces={"application/json"},
    *     tags={"facebook analytics"},
    *     @SWG\Parameter(
    *       name="profile_id",
    *       in="path",
    *       description="profile id",
    *       required=true,
    *       type="integer"
    *     ),
    *     @SWG\Parameter(
    *       name="last_days",
    *       in="path",
    *       description="Number of days ago",
    *       required=true,
    *       type="integer"
    *     ),
    *     @SWG\Response(
    *         response=200,
    *         description="Successful operation",
    *     ),
    *     @SWG\Response(
    *         response=500,
    *         description="Internal Error",
    *     )
    * )
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
    *  Distribution of Page Post Types
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     path="/facebook-analytics/{profile_id}/distribution-page-post-types?last_days={last_days}",
    *     description="analytics distribution of page post type by profile_id and number of days ago",
    *     operationId="getFacebookAnalyticsByProfileID",
    *     produces={"application/json"},
    *     tags={"facebook analytics"},
    *     @SWG\Parameter(
    *       name="profile_id",
    *       in="path",
    *       description="profile id",
    *       required=true,
    *       type="integer"
    *     ),
    *     @SWG\Parameter(
    *       name="last_days",
    *       in="path",
    *       description="Number of days ago",
    *       required=true,
    *       type="integer"
    *     ),
    *     @SWG\Response(
    *         response=200,
    *         description="Successful operation"
    *     ),
    *     @SWG\Response(
    *         response=500,
    *         description="Internal Error",
    *     )
    * )
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
