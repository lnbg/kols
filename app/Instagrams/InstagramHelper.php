<?php
namespace App\Instagrams;

use Smochin\Instagram\Crawler;

class InstagramHelper 
{
    protected $instagramCrawler;

    public function __construct(Crawler $instagramCrawler) 
    {
        $this->instagramCrawler = $instagramCrawler;
    }

    public function influencers()
    {
        return [
            'mytam.info',
            'chipupu',
            'sontungmtp',
            'bryan_joo',
            'minhhang2206',
            'bichphuongofficial',
            'missnamem'
        ];
    }

    public function getInfoInfluencerByCrawler()
    {
        $influencers = $this->influencers();
        $results = [];
        foreach ($influencers as $influencer) {
            $info = $this->instagramCrawler->getUser($influencer);
            $profile = $info->getProfile();
            $user = [
                'instagram_id' => $info->getId(),
                'name' => $info->getName(),
                'user_name' => $info->getUsername(),
                'picture' => $info->getPicture(),
                'profile' => [
                    'website' => $profile->getWebsite(),
                    'followers_count' => $profile->getFollowersCount(),
                    'follows_count' => $profile->getFollowsCount(),
                    'media_counts' => $profile->getMediaCount(),
                    'biography' => $profile->getBiography()
                ]
            ];
            $results[] = $user;
        }
        return $results;
    }
}