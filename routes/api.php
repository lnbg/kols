<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, X-Request-With, Content-Type, Accept');

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    /**
     * @todo debug influx db data 
     */
    $api->get('debug', 'App\Http\Controllers\FacebookAnalyticsController@debug');
    
    /** facebook analytics */
    $api->get('facebook-analytics', 'App\Http\Controllers\FacebookAnalyticsController@getListFacebookAnalytics');
    $api->get('facebook-analytics/{profile_id}', 'App\Http\Controllers\FacebookAnalyticsController@getFacebookAnalyticsByProfileID');
    $api->get('facebook-analytics/{profile_id}/analytics-posts-per-days', 'App\Http\Controllers\FacebookAnalyticsController@analyticsTotalPostsInDaysByProfileID');
    $api->get('facebook-analytics/{profile_id}/growth-total-fans', 'App\Http\Controllers\FacebookAnalyticsController@getFacebookGrowthOfTotalFan');
    $api->get('facebook-analytics/{profile_id}/distribution-page-post-types', 'App\Http\Controllers\FacebookAnalyticsController@analyticsDistributionOfPagePostTypeByProfileID');
    $api->get('facebook-analytics/{profile_id}/most-engaging-posts', 'App\Http\Controllers\FacebookAnalyticsController@getFacebookMostEngagingPostsByProfileID');

    /**
     * instagram analytics
     */
    $api->get('instagram-analytics/profiles', 'App\Http\Controllers\InstagramAnalyticsController@getListInstagramProfiles');
    $api->get('instagram-analytics/{username}/media', 'App\Http\Controllers\InstagramAnalyticsController@getInstagramMediaViaUsername');
    /**
     * youtube analytics
     */
    $api->get('youtube-analytics/channels', 'App\Http\Controllers\YoutubeAnalyticsController@getListYoutubeChannels');
    
});