<?php
namespace App\Facebook;

class FacebookHelper 
{
    protected $facebookSDK;

    public function __construct(Crawler $facebookSDK) 
    {
        $this->facebookSDK = $facebookSDK;
    }
}