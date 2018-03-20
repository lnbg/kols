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

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    /**
     * @todo debug influx db data 
     */
    $api->get('debug', 'App\Http\Controllers\FacebookAnalyticsController@debug');
    
    $api->get('facebook-analytics', 'App\Http\Controllers\FacebookAnalyticsController@getListFacebookAnalytics');
    $api->get('facebook-analytics/{profile_id}', 'App\Http\Controllers\FacebookAnalyticsController@getFacebookAnalyticsByProfileID');
    
    $api->get('facebook-analytics/{profile_id}/posts', 'App\Http\Controllers\FacebookAnalyticsController@analyticsTotalPostsInDaysByProfileID');
    $api->get('facebook-analytics/{profile_id}/distribution-page-post-types', 'App\Http\Controllers\FacebookAnalyticsController@analyticsDistributionOfPagePostTypeByProfileID');
});