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
    $api->get('facebook-analytics/{profile_id}/number-of-fan-posts', 'App\Http\Controllers\FacebookAnalyticsController@analyticsNumberOfFanPost');
    $api->get('facebook-analytics/{profile_id}/growth-total-fans', 'App\Http\Controllers\FacebookAnalyticsController@getFacebookGrowthOfTotalFan');
    $api->get('facebook-analytics/{profile_id}/distribution-page-post-types', 'App\Http\Controllers\FacebookAnalyticsController@analyticsDistributionOfPagePostTypeByProfileID');
    $api->get('facebook-analytics/{profile_id}/most-engaging-posts', 'App\Http\Controllers\FacebookAnalyticsController@getFacebookMostEngagingPostsByProfileID');
    $api->get('facebook-analytics/{profile_id}/evolution-of-interactions', 'App\Http\Controllers\FacebookAnalyticsController@analyticsInteractionInDaysByProfileID');
    $api->get('facebook-analytics/{profile_id}/distribution-of-interactions', 'App\Http\Controllers\FacebookAnalyticsController@analyticsDistributionOfInteractions');
    $api->get('facebook-analytics/{profile_id}/number-of-interactions-per-1kfans', 'App\Http\Controllers\FacebookAnalyticsController@analyticsInteractionPer1KFans');
    $api->get('facebook-analytics/analytics/dashboard', 'App\Http\Controllers\FacebookAnalyticsController@analyticsFacebookDashBoard');
    $api->get('facebook-analytics/{profile_id}/insigths/fans-genders-age', 'App\Http\Controllers\FacebookAnalyticsController@analyticsFacebookPageInsightsFansGendersAge');
    $api->get('facebook-analytics/{profile_id}/insigths/best-time-publish', 'App\Http\Controllers\FacebookAnalyticsController@analyticsFacebookGetTheBestTimePublished');
    
    /**
     * instagram analytics
     */
    $api->get('instagram-analytics/profiles', 'App\Http\Controllers\InstagramAnalyticsController@getAllInstagramProfiles');
    $api->get('instagram-analytics/hashtags/get-top', 'App\Http\Controllers\InstagramAnalyticsController@analyticsInstagramGetTopHashTags');
    $api->get('instagram-analytics/hashtags/{tag_name}/media', 'App\Http\Controllers\InstagramAnalyticsController@getInstagramMediaByHashTag');
    $api->get('instagram-analytics/{profile_id}', 'App\Http\Controllers\InstagramAnalyticsController@getInstagramProfileAnalyticsByProfileID');
    $api->get('instagram-analytics/{username}/media', 'App\Http\Controllers\InstagramAnalyticsController@getInstagramMediaViaUsername');
    $api->get('instagram-analytics/{profile_id}/growth-total-followers', 'App\Http\Controllers\InstagramAnalyticsController@getInstagramGrowthOfTotalFollowers');
    $api->get('instagram-analytics/{profile_id}/distribution-profile-media-types', 'App\Http\Controllers\InstagramAnalyticsController@analyticsDistributionOfProfileMediaType');
    $api->get('instagram-analytics/{profile_id}/most-engaging-media', 'App\Http\Controllers\InstagramAnalyticsController@getInstagramMostEngagingMediaByProfileID');
    $api->get('instagram-analytics/{profile_id}/analytics-media-per-days', 'App\Http\Controllers\InstagramAnalyticsController@analyticsTotalMediaInDaysByInstagramProfileID');
    $api->get('instagram-analytics/{profile_id}/evolution-of-interactions', 'App\Http\Controllers\InstagramAnalyticsController@analyticsInstagramInteractionInDaysByProfileID');
    $api->get('instagram-analytics/{profile_id}/distribution-of-interactions', 'App\Http\Controllers\InstagramAnalyticsController@analyticsInstagramDistributionOfInteraction');
    $api->get('instagram-analytics/{profile_id}/number-of-interactions-per-1kfollowers', 'App\Http\Controllers\InstagramAnalyticsController@analyticsInstagramInteractionInDayPer1KFollowers');
    $api->get('instagram-analytics/{profile_id}/get-all-hashtags', 'App\Http\Controllers\InstagramAnalyticsController@analyticsInstagramGetAllTagsByProfileID');
    $api->get('instagram-analytics/analytics/dashboard', 'App\Http\Controllers\InstagramAnalyticsController@analyticsInstagramDashBoard');
    
    /**
     * youtube analytics
     */
    $api->get('youtube-analytics/channels', 'App\Http\Controllers\YoutubeAnalyticsController@getListYoutubeChannels');
    
});