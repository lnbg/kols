<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Repositories\YoutubeChannelRepository;
use App\Repositories\YoutubeVideoRepository;

use App\Transformers\YoutubeChannelTransformer;
use App\Transformers\YoutubeVideoTransformer;

use App\Criteria\Youtube\GetListYoutubeChannelsCriteria;

use App\InfluxDB\InfluxDB;


/**
 * Class YoutubeAnalyticsController.
 *
 * @package namespace App\Http\Controllers;
 * 
 */
class YoutubeAnalyticsController extends BaseController
{
    /**
     * @var YoutubeChannelRepository
     */
    protected $youtubeChannelRepository;

    /**
     * @var YoutubeVideoRepository
     */
    protected $youtubeVideoRepository;

    /**
     * @var InfluxDB
     */
    protected $influxDB;
    /**
     * FacebookProfilesController constructor.
     *
     * @param YoutubeChannelRepository $repository
     * @param YoutubeVideoRepository $repository
     * @param InfluxDB object
     */
    public function __construct(YoutubeChannelRepository $youtubeChannelRepository, 
    YoutubeVideoRepository $youtubeVideoRepository,
    influxDB $influxDB)
    {
        $this->youtubeChannelRepository = $youtubeChannelRepository;
        $this->youtubeVideoRepository = $youtubeVideoRepository;
        $this->influxDB = $influxDB;
    }

    /**
     * Youtube Overview: List Youtube channels
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @SWG\Get(
     *     path="/youtube-analytics/channels",
     *     description="get list youtube channels",
     *     operationId="getListYoutubeChannels",
     *     produces={"application/json"},
     *     tags={"youtube analytics"},
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
    public function getListYoutubeChannels()
    {
        try {
            $this->youtubeChannelRepository->pushCriteria(GetListYoutubeChannelsCriteria::class);
            $youtubeChannels = $this->youtubeChannelRepository->paginate();
            return $this->response()->paginator($youtubeChannels, new YoutubeChannelTransformer);
        } catch (\Execption $e) {
            return $this->response()->errorInternal();
        }
    }    
}
