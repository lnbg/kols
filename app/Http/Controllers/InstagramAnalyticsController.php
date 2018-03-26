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
        return \Response::json($result, 200);
    }
}
