<?php
namespace App\Facebook;

use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
class FacebookHelper 
{
    protected $facebookSDK;

    public function __construct(LaravelFacebookSdk $facebookSDK) 
    {
        $this->facebookSDK = $facebookSDK;
    }

    /**
     * get page fans age
     *
     * @param [type] $facebookPageID
     * @param [type] $accessToken
     * @return void
     */
    public function getPageFansAge($facebookPageID, $accessToken)
    {
        $query = '/' . $facebookPageID . '/insights?metric=page_fans_gender_age&since=' . date('Y-m-d');
        $response = $this->facebookSDK->get($query, $accessToken);
        $data = [
            "13-17" => 0,
            "18-24" => 0,
            "25-34" => 0,
            "35-44" => 0,
            "45-54" => 0,
            "55-64" => 0,
            "65+" => 0,
        ];
        $response = $response->getDecodedBody();
        if (count($response) > 0) {
            $response = $response["data"][0]["values"];
            if (count($response) > 0) {
                $response = $response[0]["value"];
                foreach ($response as $key => $res) {
                    $key = explode('.', $key);
                    if (count($key) > 1) {
                        $key = $key[1];
                    }
                    $data[$key] += $res;
                }
            }
        }
        return $data;
    }
}