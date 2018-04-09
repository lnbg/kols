<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Instagrams\InstagramHelper;

use App\Repositories\InstagramMediaRepository;
use App\Repositories\InstagramProfileRepository;

use App\Transformers\InstagramMediaTransformer;
use App\Transformers\InstagramProfileTransformer;
use App\Transformers\InstagramOverviewTransformer;

use App\Criteria\Instagram\GetAllInstagramProfilesCriteria;
use App\Criteria\Instagram\GetInstagramProfileByProfileIDCriteria;
use App\Criteria\Instagram\GetDistributionOfProfilePostTypeCriteria;
use App\Criteria\Instagram\GetInstagramMostEngagingPostsByProfileIDCriteria;
use App\Criteria\Instagram\GetInstagramMediaByTagCriteria;

use App\InfluxDB\InfluxDB;

/**
 * Class InstagramAnalyticsController.
 *
 * @package namespace App\Http\Controllers;
 * 
 */
class InstagramAnalyticsController extends BaseController
{
    /**
     * InstagramHelper
     *
     * @var InstagramHelper
     */
    protected $instagramHelper;

    /**
     * @var InstagramProfileRepository
     */
    protected $instagramProfileRepository;

     /**
     * @var InstagramMediaRepository
     */
    protected $instagramMediaRepository;
    
     /**
     * @var influxDB
     */
    protected $influxDB;

    public function __construct(InstagramHelper $instagramHelper, 
    InstagramMediaRepository $instagramMediaRepository,
    InstagramProfileRepository $instagramProfileRepository,
    influxDB $influxDB)
    {
        $this->instagramHelper = $instagramHelper;
        $this->instagramProfileRepository = $instagramProfileRepository;
        $this->instagramMediaRepository = $instagramMediaRepository;
        $this->influxDB = $influxDB;
    }

    /**
     * Instagram Overview: List Instagram Profiles
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     path="/instagram-analytics/profiles",
     *     description="get list instagram profiles",
     *     operationId="getListInstagramProfiles",
     *     produces={"application/json"},
     *     tags={"instagram analytics"},
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
    public function getAllInstagramProfiles() 
    {
        try {
            $this->instagramProfileRepository->pushCriteria(getAllInstagramProfilesCriteria::class);
            $instagramAnalytics = $this->instagramProfileRepository->paginate();
            return $this->response()->paginator($instagramAnalytics, new InstagramOverviewTransformer);
        } catch (\Exception $ex) {
            //return $this->response()->errorInternal();
            return $ex;
        }
    }

    /**
    *  The Detail of Instagram Profile
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     path="/instagram-analytics/{profile_id}",
    *     description="get detail of instagram profile via profile id",
    *     operationId="getInstagramProfileAnalyticsByProfileID",
    *     produces={"application/json"},
    *     tags={"instagram analytics"},
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
    public function getInstagramProfileAnalyticsByProfileID($profile_id)
    {
        try {
            $profileID = $profile_id;
            $this->instagramProfileRepository->pushCriteria(new GetInstagramProfileByProfileIDCriteria($profileID));
            $posts = $this->instagramProfileRepository->first();
            return $this->response()->item($posts, new InstagramProfileTransformer);
        } catch (\Exception $e) {
            //return $this->response()->errorInternal();
            return $ex;
        }
    }

    /**
    *  Followers overview: Growth of total followers
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     path="/instagram-analytics/{profile_id}/growth-total-followers?last_days={last_days}",
    *     description="analytics growth of total followers in days via profile_id and number of days ago",
    *     operationId="getInstagramGrowthOfTotalFollowers",
    *     produces={"application/json"},
    *     tags={"instagram analytics"},
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
    public function getInstagramGrowthOfTotalFollowers($profile_id, Request $request)
    {
        try {
            $profileID = $profile_id;
            $lastDays = isset($request->last_days) ? $request->last_days : 30;
            $instagramID = $this->instagramProfileRepository->find($profileID)->instagram_id;
            $analyticsDatas = $this->influxDB->getGrowthOfTotalFollowers($instagramID, $lastDays);
            return $this->response()->array(['data' => $analyticsDatas]);
        } catch (\Exception $ex) {
            return $this->response()->errorInternal();
        }
    }

    /**
    *  Most engaging posts via profile id
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     path="/instagram-analytics/{profile_id}/most-engaging-media?last_days={last_days}",
    *     description="get most engaging posts via profile id",
    *     operationId="getInstagramMostEngagingMediaByProfileID",
    *     produces={"application/json"},
    *     tags={"instagram analytics"},
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
    public function getInstagramMostEngagingMediaByProfileID($profile_id, Request $request)
    {
        try {
            $profileID = $profile_id;
            $lastDays = isset($request->last_days) ? $request->last_days : 30;
            $this->instagramMediaRepository->pushCriteria(new GetInstagramMostEngagingPostsByProfileIDCriteria($profileID, $lastDays));
            $posts = $this->instagramMediaRepository->get();
            return $this->response()->collection($posts, new InstagramMediaTransformer);
        } catch (\Exception $e) {
           // return $this->response()->errorInternal();
            return $e;
        }
    }

    /**
     * Instagram Overview: List Media Instagram
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     path="/instagram-analytics/{username}/media",
     *     description="get all media via username",
     *     operationId="getInstagramMediaViaUsername",
     *     produces={"application/json"},
     *     tags={"instagram analytics"},
     *     @SWG\Parameter(
     *       name="username",
     *       in="path",
     *       description="instagram username",
     *       required=true,
     *       type="string"
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
    public function getInstagramMediaViaUsername($username)
    {
        $result = $this->instagramHelper->getPostsOfInstagramByUserName($username);
        return \Response::json(['data' => $result], 200);
    }


    /**
    *  Distribution of Instagram Profile Media Types
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     path="/instagram-analytics/{profile_id}/distribution-profile-media-types?last_days={last_days}",
    *     description="analytics distribution of profile media type via profile_id and number of days ago",
    *     operationId="analyticsDistributionOfProfileMediaType",
    *     produces={"application/json"},
    *     tags={"instagram analytics"},
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
    public function analyticsDistributionOfProfileMediaType($profile_id, Request $request)
    {
        try {
            $profileID = $profile_id;
            $lastDays = $request->last_days;
            $this->instagramMediaRepository->pushCriteria(new GetDistributionOfProfilePostTypeCriteria($profileID));
            $analyticsDatas = $this->instagramMediaRepository->get();
            return $this->response()->array(['data' => $analyticsDatas]);
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    /**
    *  Content Overview: Number of Instagram media
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     path="/instagram-analytics/{profile_id}/analytics-media-per-days?last_days={last_days}",
    *     description="analytics total media in days via profile_id and number of days ago",
    *     operationId="analyticsTotalMediaInDaysByInstagramProfileID",
    *     produces={"application/json"},
    *     tags={"instagram analytics"},
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
    public function analyticsTotalMediaInDaysByInstagramProfileID($profile_id, Request $request)
    {
        try {
            $profileID = $profile_id;
            $lastDays = $request->last_days;
            $instagramID = $this->instagramProfileRepository->find($profileID)->instagram_id;
            $analyticsDatas = $this->influxDB->analyticsTotalMediaInDaysByInstagramID($instagramID, $lastDays);
            return $this->response()->array(['data' => $analyticsDatas]);
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    /**
    *  evolution of interactions
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     path="/instagram-analytics/{profile_id}/evolution-of-interactions?last_days={last_days}",
    *     description="analytics evolution of interactions via profile_id and number of days ago",
    *     operationId="analyticsInstagramInteractionInDaysByProfileID",
    *     produces={"application/json"},
    *     tags={"instagram analytics"},
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
    public function analyticsInstagramInteractionInDaysByProfileID($profile_id, Request $request)
    {
        try {
            $profileID = $profile_id;
            $lastDays = $request->last_days;
            $instagramID = $this->instagramProfileRepository->find($profileID)->instagram_id;
            $analyticsDatas = $this->influxDB->analyticsInstagramInteractionInDaysByProfileID($instagramID, $lastDays);
            return $this->response()->array(['data' => $analyticsDatas]);
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    /**
    *  distribution of interactions
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     path="/instagram-analytics/{profile_id}/distribution-of-interactions?last_days={last_days}",
    *     description="analytics distribution of interactions via profile_id and number of days ago",
    *     operationId="analyticsInstagramDistributionOfInteraction",
    *     produces={"application/json"},
    *     tags={"instagram analytics"},
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
    public function analyticsInstagramDistributionOfInteraction($profile_id, Request $request)
    {
        try {
            $profileID = $profile_id;
            $lastDays = $request->last_days;
            $instagramID = $this->instagramProfileRepository->find($profileID)->instagram_id;
            $analyticsDatas = $this->influxDB->instagramDistributionOfInteractions($instagramID, $lastDays);
            return $this->response()->array(['data' => $analyticsDatas]);
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    /**
    *  number of interaction per 1k fans
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     path="/instagram-analytics/{profile_id}/number-of-interactions-per-1kfollowers?last_days={last_days}",
    *     description="number of interactions per 1k followers via profile_id and number of days ago",
    *     operationId="analyticsInstagramInteractionInDayPer1KFollowers",
    *     produces={"application/json"},
    *     tags={"instagram analytics"},
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
    public function analyticsInstagramInteractionInDayPer1KFollowers($profile_id, Request $request)
    {
        try {
            $profileID = $profile_id;
            $lastDays = $request->last_days;
            $instagramID = $this->instagramProfileRepository->find($profileID)->instagram_id;
            $analyticsDatas = $this->influxDB->analyticsInstagramInteractionInDayPer1KFollowers($instagramID, $lastDays);
            return $this->response()->array(['data' => $analyticsDatas]);
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    /**
    *  get all tags by profile id
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     path="/instagram-analytics/{profile_id}/get-all-hashtags",
    *     description="get all hashtags",
    *     operationId="analyticsInstagramGetAllTagsByProfileID",
    *     produces={"application/json"},
    *     tags={"instagram analytics"},
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
    public function analyticsInstagramGetAllTagsByProfileID($profile_id)
    {
        try {
            $profileID = $profile_id;
            $instagramID = $this->instagramProfileRepository->find($profileID)->instagram_id;
            $analyticsDatas = $this->influxDB->analyticsInstagramGetAllTagsByProfileID($instagramID);
            return $this->response()->array(['data' => $analyticsDatas]);
        } catch (\Exception $ex) {
            return $ex;
        }
    }

    /**
    *  get top hashtags
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     path="/instagram-analytics/hashtags/get-top",
    *     description="get top hashtags (top 10)",
    *     operationId="analyticsInstagramGetTopHashTags",
    *     produces={"application/json"},
    *     tags={"instagram analytics"},
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
    public function analyticsInstagramGetTopHashTags()
    {
        try {
            $analyticsDatas = $this->influxDB->analyticsInstagramGetTopHashTags();
            return $this->response()->array(['data' => $analyticsDatas]);
        } catch (\Exception $ex) {
            return $ex;
        }
    }


    /**
    *  get media by hashtags
    *
    * @return \Illuminate\Http\JsonResponse
    *
    * @SWG\Get(
    *     path="/instagram-analytics/hashtags/{tag_name}/media",
    *     description="get media by hashtags",
    *     operationId="getInstagramMediaByHashTag",
    *     produces={"application/json"},
    *     tags={"instagram analytics"},
    *     @SWG\Parameter(
    *       name="tag_name",
    *       in="path",
    *       description="tag_name",
    *       required=true,
    *       type="string"
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
    public function getInstagramMediaByHashTag($tag_name) 
    {
        try {
            $this->instagramMediaRepository->pushCriteria(new GetInstagramMediaByTagCriteria($tag_name));
            $analyticsDatas = $this->instagramMediaRepository->paginate(10);
            return $this->response()->paginator($analyticsDatas, new InstagramMediaTransformer);
        } catch (\Exception $ex) {
            return $ex;
        }
    }
}
