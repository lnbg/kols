<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\FacebookProfileRepository;
use App\Repositories\FacebookPostRepository;

use App\Transformers\FacebookProfileTransformer;
use App\Transformers\FacebookAnalyticsOverviewTransformer;
use App\Transformers\FacebookPostTransformer;

use App\Criteria\Facebook\GetFacebookAnalyticsCriteria;
use App\Criteria\Facebook\GetFacebookProfileByIDCriteria;
use App\Criteria\Facebook\GetFacebookMostEngagingPostsByProfileIDCriteria;
use App\Criteria\Facebook\GetFacebookDistributionOfPagePostTypeCriteria;
use App\Criteria\Facebook\GetTopFacebookFansCriteria;

use App\InfluxDB\InfluxDB;
use App\Facebook\FacebookHelper;


/**
 * Class FacebookAnalyticsController.
 *
 * @package namespace App\Http\Controllers;
 * 
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
     * @var FacebookHelper
     *
     * @var [type]
     */
    protected $facebookHelper;
    /**
     * FacebookProfilesController constructor.
     *
     * @param FacebookProfileRepository $repository
     */
    public function __construct(FacebookProfileRepository $facebookProfileRepository, 
    FacebookPostRepository $facebookPostRepository,
    influxDB $influxDB,
    FacebookHelper $facebookHelper)
    {
        $this->facebookProfileRepository = $facebookProfileRepository;
        $this->facebookPostRepository = $facebookPostRepository;
        $this->influxDB = $influxDB;
        $this->facebookHelper = $facebookHelper;
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
            $facebookAnalytics = $this->facebookProfileRepository->paginate();
            return $this->response()->paginator($facebookAnalytics, new FacebookAnalyticsOverviewTransformer);
        } catch (\Execption $e) {
            return $this->response()->errorInternal();
        }
    }

    /**
    *  The Detail of Page/Profile
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     path="/facebook-analytics/{profile_id}",
    *     description="get detail of facebook profile via profile id",
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
    public function getFacebookAnalyticsByProfileID($profile_id)
    {
        try {
            $profileID = $profile_id;
            $this->facebookProfileRepository->pushCriteria(new GetFacebookProfileByIDCriteria($profileID));
            $posts = $this->facebookProfileRepository->first();
            return $this->response()->item($posts, new FacebookProfileTransformer);
        } catch (\Exception $e) {
            return $this->response()->errorInternal();
        }
    }

    /**
    *  Most engaging posts via profile id
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     path="/facebook-analytics/{profile_id}/most-engaging-posts?last_days={last_days}",
    *     description="get most engaging posts post via profile id",
    *     operationId="getFacebookMostEngagingPostsByProfileID",
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
    public function getFacebookMostEngagingPostsByProfileID($profile_id, Request $request)
    {
        try {
            $profileID = $profile_id;
            $lastDays = isset($request->last_days) ? $request->last_days : 30;
            $this->facebookPostRepository->pushCriteria(new GetFacebookMostEngagingPostsByProfileIDCriteria($profileID, $lastDays));
            $posts = $this->facebookPostRepository->get();
            return $this->response()->collection($posts, new FacebookPostTransformer);
        } catch (\Exception $e) {
            return $this->response()->errorInternal();
        }
    }

    
    /**
    *  Fan overview: Growth of total fans
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     path="/facebook-analytics/{profile_id}/growth-total-fans?last_days={last_days}",
    *     description="analytics growth of total fans in days via profile_id and number of days ago",
    *     operationId="getFacebookGrowthOfTotalFan",
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
    public function getFacebookGrowthOfTotalFan($profile_id, Request $request)
    {
        try {
            $profileID = $profile_id;
            $lastDays = isset($request->last_days) ? $request->last_days : 30;
            $facebookID = $this->facebookProfileRepository->find($profileID)->facebook_id;
            $analyticsDatas = $this->influxDB->getGrowthOfTotalFan($facebookID, $lastDays);
            return $this->response()->array(['data' => $analyticsDatas]);
        } catch (\Exception $ex) {
            return $this->response()->errorInternal();
        }
    }

    /**
    *  Content Overview: Number of Page Posts
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     path="/facebook-analytics/{profile_id}/analytics-posts-per-days?last_days={last_days}",
    *     description="analytics total posts in days via profile_id and number of days ago",
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
            return $this->response()->array(['data' => $analyticsDatas]);
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
    *     description="analytics distribution of page post type via profile_id and number of days ago",
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
            $this->facebookPostRepository->pushCriteria(new GetFacebookDistributionOfPagePostTypeCriteria($profileID));
            $analyticsDatas = $this->facebookPostRepository->get();
            return $this->response()->array(['data' => $analyticsDatas]);
        } catch (\Exception $ex) {
            return $this->response()->errorInternal();
        }
    }

    /**
    *  Number of fan post
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     path="/facebook-analytics/{profile_id}/number-of-fan-posts?last_days={last_days}",
    *     description="analytics number of fan posts via profile_id and number of days ago",
    *     operationId="analyticsNumberOfFanPost",
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
    public function analyticsNumberOfFanPost($profile_id, Request $request)
    {
        try {
            $profileID = $profile_id;
            $lastDays = $request->last_days;
            $facebookID = $this->facebookProfileRepository->find($profileID)->facebook_id;
            $analyticsDatas = $this->influxDB->analyticsNumberOfFanPosts($facebookID, $lastDays);
            return $this->response()->array(['data' => $analyticsDatas]);
        } catch (\Exception $ex) {
            return $this->response()->errorInternal();
        }
    }

    /**
    *  evolution of interactions
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     path="/facebook-analytics/{profile_id}/evolution-of-interactions?last_days={last_days}",
    *     description="evolution of interactions via profile_id and number of days ago",
    *     operationId="analyticsInteractionInDaysByProfileID",
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
    public function analyticsInteractionInDaysByProfileID($profile_id, Request $request)
    {
        // try {
            $profileID = $profile_id;
            $lastDays = $request->last_days;
            $facebookID = $this->facebookProfileRepository->find($profileID)->facebook_id;
            $analyticsDatas = $this->influxDB->analyticsInteractionInDaysByProfileID($facebookID, $lastDays);
            return $this->response()->array(['data' => $analyticsDatas]);
        // } catch (\Exception $ex) {
        //     return $this->response()->errorInternal();
        // }
    }
    
    /**
    *  distribution of interaction
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     path="/facebook-analytics/{profile_id}/distribution-of-interactions?last_days={last_days}",
    *     description="distribution of interactions via profile_id and number of days ago",
    *     operationId="analyticsDistributionOfInteractions",
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
    public function analyticsDistributionOfInteractions($profile_id, Request $request)
    {
        $profileID = $profile_id;
        $lastDays = $request->last_days;
        $facebookID = $this->facebookProfileRepository->find($profileID)->facebook_id;
        $analyticsDatas = $this->influxDB->distributionOfInteractions($facebookID, $lastDays);
        return $this->response()->array(['data' => $analyticsDatas]);
    }

    /**
    *  number of interaction per 1k fans
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     path="/facebook-analytics/{profile_id}/number-of-interactions-per-1kfans?last_days={last_days}",
    *     description="number of interactions per 1k fans via profile_id and number of days ago",
    *     operationId="analyticsInteractionPer1KFans",
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
    public function analyticsInteractionPer1KFans($profile_id, Request $request)
    {
        $profileID = $profile_id;
        $lastDays = $request->last_days;
        $facebookID = $this->facebookProfileRepository->find($profileID)->facebook_id;
        $analyticsDatas = $this->influxDB->analyticsInteractionInDayPer1KFans($facebookID, $lastDays);
        return $this->response()->array(['data' => $analyticsDatas]);
    }

    /**
    *  facebook analytics data for dashboard
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     path="/facebook-analytics/analytics/dashboard",
    *     description="facebook analytics data for dashboard",
    *     operationId="analyticsFacebookDashBoard",
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
    public function analyticsFacebookDashBoard()
    {
        $this->facebookProfileRepository->pushCriteria(GetTopFacebookFansCriteria::class);
        $facebookOverviewTransfomerInstance = new FacebookAnalyticsOverviewTransformer();
        $topFans = $facebookOverviewTransfomerInstance->transformArray($this->facebookProfileRepository->get());
        $now = date('Y-m-d');
        $affectiveDate = date('Y-m', strtotime($now . '-1 months'));
        return $this->response()->array([
            'date' => $affectiveDate,
            'top_fans' => $topFans,
        ]);
    }

    /**
    *  facebook get page fans age
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     path="/facebook-analytics/fans-age",
    *     description="facebook analytics fans age",
    *     operationId="analyticsFacebookPageFansAge",
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
    public function analyticsFacebookPageFansAge()
    {
        $facebookProfile = $this->facebookProfileRepository->find(18);
        return $this->response()->array([
            'data' => $this->facebookHelper->getPageFansAge($facebookProfile->facebook_id, $facebookProfile->access_token)
        ]);
    }
     /**
     * influx db debugger
     * @uses Influx database
     * @return \Illuminate\Http\Response
     */
    public function debug()
    {
        $facebookProfile = $this->facebookProfileRepository->find(18);
        return $this->response()->array([
            'data' => $this->facebookHelper->getPageFansGenderAge($facebookProfile->facebook_id, $facebookProfile->access_token)
        ]);
    }


}
