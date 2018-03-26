<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Instagrams\InstagramHelper;

/**
 * Class InstagramAnalyticsController.
 *
 * @package namespace App\Http\Controllers;
 * 
 */
class InstagramAnalyticsController extends BaseController
{
    
    protected $instagramHelper;

    public function __construct(InstagramHelper $instagramHelper)
    {
        $this->instagramHelper = $instagramHelper;
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
    public function getListInstagramProfiles()
    {
        $result = $this->instagramHelper->getInfoInfluencerByCrawler();
        return \Response::json(['data' => $result], 200);
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
}
