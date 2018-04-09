<?php
namespace App\InfluxDB;

use DateTime;
use InfluxDB\Client as InfluxDBClient;
use App\Entities\FacebookProfile;
use App\Entities\InstagramProfile;

class InfluxDB {

    protected $influxDb;
    protected $database;

    public function __construct() 
    {
        $this->influxDb = new InfluxDBClient(env('INFLUXDB_HOST'), env('INFLUXDB_PORT'));
        $this->database = $this->influxDb->selectDB('analytics');
    }

    /******************************** FACEBOOK **********************************/
    /**
     * count total fans in days with profile id
     * @param int $profileID
     * @return json
     */
    public function getAllProfileFan($profileID)
    {
        // executing a query will yield a resultset object
        $result = $this->database->query("SELECT * FROM facebook_profile_fans WHERE profile_id = '" . $profileID . "'");
        // get the points from the resultset yields an array
        $points = $result->getPoints();
        return $points;
    }

    /**
     * get all record for grouth of total fan via profile id and last day
     *
     * @param [type] $profileID
     * @param [type] $lastDays
     * @return void
     */
    public function getGrowthOfTotalFan($profileID, $lastDays)
    {
         // executing a query will yield a resultset object
        $query = "SELECT last(value) as value FROM facebook_profile_fans WHERE profile_id = '" . $profileID . "' and time > now() - " . $lastDays . "d group by time(1d) tz('Asia/Saigon')";
        $result = $this->database->query($query);
         // get the points from the resultset yields an array
        $points = $result->getPoints();
        $currentPoint = 0;
        $oldPoint = 0;
        $results = [];
        $minFans = 0;
        $maxFans = 0;
        $maxChangeFans = 0;
        $totalChangeFans = 0;
        foreach ($points as $point) {
            if (isset($point['value'])) {
                if ($currentPoint == 0 && $oldPoint == 0) 
                {
                    $currentPoint = $oldPoint = $point['value'];
                    $minFans = $point['value'];
                    $maxFans = $point['value'];
                } else {
                    $currentPoint = $point['value'];
                    if ($point['value'] > $maxFans) {
                        $maxFans = $point['value'];
                    }
                    if ($point['value'] < $minFans) {
                        $minFans = $point['value'];
                    }
                }
            }
            $growthValue = $currentPoint - $oldPoint;
            if ($growthValue > $maxChangeFans) {
                $maxChangeFans = $growthValue;
            }
            $totalChangeFans += $growthValue;
            $oldPoint = $currentPoint;
            $date = new DateTime($point['time']);
            $item = [
                'time' =>  $date->format('Y-m-d'),
                'value' => $growthValue
            ];
            $results[] = $item;
        }
        
        return [
            'max_change_fans' => $maxChangeFans,
            'total_change_fans' => $totalChangeFans,
            'max_fans' => $maxFans,
            'min_fans' => $minFans,
            'data' => $results, 
            'original_data' => $points
        ];
    }

    /**
     * count total posts in days with profile id
     * @param int $profileID
     * @return json
     */
    public function analyticsTotalPostInDaysByFacebookID($profileID, $lastDays)
    {
        // executing a query will yield a resultset object
        $query = "SELECT SUM(value) FROM facebook_profile_new_posts WHERE profile_id = '" . $profileID . "' and time > now() - " . $lastDays . "d GROUP BY time(1d) tz('Asia/Saigon')";
        $result = $this->database->query($query);
        // get the points from the resultset yields an array
        $points = $result->getPoints();
        return $points;
    }

    /**
     * analytics distribution of facebook page posts type
     *
     * @param [type] $profileID
     * @param [type] $lastDays
     * @return void
     */
    public function analyticsDistributionOfPagePostType($profileID, $lastDays)
    {
        // executing a query will yield a resultset object
        $query = "SELECT post_type, SUM(value) FROM facebook_profile_new_posts WHERE profile_id = '" . $profileID . "' and time > now() - " . $lastDays . "d GROUP BY post_type";
        $result = $this->database->query($query);
        // get the points from the resultset yields an array
        $points = $result->getPoints();
        return $points;
    } 

    /**
     * analytics number of facebook fan posts
     *
     * @param [type] $profileID
     * @param [type] $lastDays
     * @return void
     */
    public function analyticsNumberOfFanPosts($profileID, $lastDays)
    {
        // executing a query will yield a resultset object
        $query = "SELECT SUM(value) FROM facebook_profile_new_fan_posts WHERE profile_id = '" . $profileID . "' GROUP BY time(1d) FILL(none) tz('Asia/Saigon')";
        $result = $this->database->query($query);
        // get the points from the resultset yields an array
        $points = $result->getPoints();
        return $points;
    }

    /**
     * analytics interaction in day
     *
     * @param [type] $profileID
     * @param [type] $lastDays
     * @return void
     */
    public function analyticsInteractionInDaysByProfileID($profileID, $lastDays)
    {
        $query = "SELECT SUM(value) from facebook_post_interactions WHERE profile_id = '" . $profileID . "' GROUP BY interaction_type, time(1d) fill(0) tz('Asia/Saigon')";
        $result = $this->database->query($query);
        // get the points from the resultset yields an array
        $series = $result->getSeries();
        $results = [];
        $reactions = [];
        $comments = [];
        $shares = [];
        $times = [];
        $interactions = [];
        $totalInteractions = 0;

        foreach ($series as $seri) {
            $tag = $seri['tags']['interaction_type'];
            foreach ($seri['values'] as $val) {
                if ($tag == 'comment') {
                    $comments[$val[0]] = $val[1];
                } else if ($tag == 'share') {
                    $shares[$val[0]] = $val[1];
                } else {
                    if (isset($reactions[$val[0]])) {
                        $reactions[$val[0]] += $val[1];
                    } else {
                        $reactions[$val[0]] = $val[1];
                    }
                }
                if (isset($interactions[$val[0]])) {
                    $interactions[$val[0]] += $val[1];
                } else {
                    $interactions[$val[0]] = $val[1];
                }
                $totalInteractions += $val[1];
            }
        }
        $maxInterctionValue = max($interactions);
        $keyOfMaxInteraction = array_search($maxInterctionValue, $interactions);
        $results = [
            'comments' => $comments,
            'shares' => $shares,
            'reactions' => $reactions,
            'max_interactions_on' => [
                'time' => $keyOfMaxInteraction,
                'value' => $maxInterctionValue
            ],
            'average_interactions_per_day' => $totalInteractions / $lastDays
        ];
        return $results;
    }

    /**
     * analytics interaction in day per 1k fan
     *
     * @param [type] $profileID
     * @param [type] $lastDays
     * @return void
     */
    public function analyticsInteractionInDayPer1KFans($profileID, $lastDays)
    {
        $totalFans = FacebookProfile::where('facebook_id', '=', $profileID)->first()->fan_count;
        $query = "SELECT (SUM(value) * 1000 / " . $totalFans . ") as value from facebook_post_interactions WHERE profile_id = '" . $profileID . "' GROUP BY time(1d) FILL(0) tz('Asia/Saigon')";
        $result = $this->database->query($query);
        // get the points from the resultset yields an array
        $points = $result->getPoints();
        foreach ($points as &$point) {
            $point['value'] = round($point['value'], 2);
        }
        return $points;
    }

    /**
     * distribution of interaction
     *
     * @param [type] $profileID
     * @param [type] $lastDays
     * @return void
     */
    public function distributionOfInteractions($profileID, $lastDays)
    {
        // executing a query will yield a resultset object
        $query = "SELECT SUM(value) FROM facebook_post_interactions WHERE profile_id = '" . $profileID . "' and time > now() - " . $lastDays . "d GROUP BY interaction_type";
        $result = $this->database->query($query);
        // get the points from the resultset yields an array
        $series = $result->getSeries();
        $reactions = 0;
        $comments = 0;
        $shares = 0;
        $total = 0;
        foreach ($series as $seri) {
            if ($seri['tags']['interaction_type'] == 'comment')
            {
                $comments = $seri['values'][0][1];
            } else if ($seri['tags']['interaction_type'] == 'share')
            {
                $shares = $seri['values'][0][1];
            } else {
                $reactions += $seri['values'][0][1];
            }
            $total += $seri['values'][0][1];
        }
        return [
            'comments' => [
                'value' => $comments,
                'percentage' => round($comments / $total, 2)
            ],
            'shares' => [
                'value' => $shares,
                'percentage' => round($shares / $total, 2)
            ],
            'reactions' => [
                'value' => $reactions,
                'percentage' => round($reactions / $total, 2)
            ]
        ];
    }

    /******************************* INSTAGRAM **********************************/

    /**
     * count total media in days with instagram profile id
     * @param int $profileID
     * @return json
     */
    public function analyticsTotalMediaInDaysByInstagramID($profileID, $lastDays)
    {
        // executing a query will yield a resultset object
        $query = "SELECT last(value) as value FROM instagram_account_media WHERE account_id = '" . $profileID . "' and time > now() - " . $lastDays . "d group by time(1d) fill(0) tz('Asia/Saigon')";
        $result = $this->database->query($query);
        // get the points from the resultset yields an array
        $points = $result->getPoints();
        return $points;
    }   
    
    /**
     * get all record for grouth of total fan via profile id and last day
     *
     * @param [type] $profileID
     * @param [type] $lastDays
     * @return void
     */
    public function getGrowthOfTotalFollowers($profileID, $lastDays)
    {
         // executing a query will yield a resultset object
        $query = "SELECT last(value) FROM instagram_account_followers WHERE account_id = '" . $profileID . "' and time > now() - " . $lastDays . "d  GROUP BY time(1d) FILL(0)";
        $result = $this->database->query($query);
         // get the points from the resultset yields an array
        $points = $result->getPoints();
        $currentPoint = 0;
        $oldPoint = 0;
        $results = [];
        $minFans = 0;
        $maxFans = 0;
        $maxChangeFans = 0;
        $totalChangeFans = 0;
        foreach ($points as $point) {
            if (isset($point['last'])) {
                if ($currentPoint == 0 && $oldPoint == 0) 
                {
                    $currentPoint = $oldPoint = $point['last'];
                    $minFans = $point['last'];
                    $maxFans = $point['last'];
                } else {
                    $currentPoint = $point['last'];
                    if ($point['last'] > $maxFans) {
                        $maxFans = $point['last'];
                    }
                    if ($point['last'] < $minFans) {
                        $minFans = $point['last'];
                    }
                }
            }
            $growthValue = $currentPoint - $oldPoint;
            if ($growthValue > $maxChangeFans) {
                $maxChangeFans = $growthValue;
            }
            $totalChangeFans += $growthValue;
            $oldPoint = $currentPoint;
            $date = new DateTime($point['time']);
            $item = [
                'time' =>  $date->format('Y-m-d'),
                'value' => $growthValue
            ];
            $results[] = $item;
        }
        
        return [
            'max_change_fans' => $maxChangeFans,
            'total_change_fans' => $totalChangeFans,
            'max_fans' => $maxFans,
            'min_fans' => $minFans,
            'data' => $results
        ];
    }
    
    /**
     * analytics instagram interaction in days
     *
     * @param [type] $profileID
     * @param [type] $lastDays
     * @return void
     */
    public function analyticsInstagramInteractionInDaysByProfileID($profileID, $lastDays)
    {
        $query = "SELECT SUM(value) from instagram_media_interactions WHERE account_id = '" . $profileID . "' GROUP BY interaction_type, time(1d) tz('Asia/Saigon')";
        $result = $this->database->query($query);
        // get the points from the resultset yields an array
        $series = $result->getSeries();
        $results = [];
        $likes = [];
        $comments = [];
        $interactions = [];
        $totalInteractions = 0;

        foreach ($series as $seri) {
            $tag = $seri['tags']['interaction_type'];
            foreach ($seri['values'] as $val) {
                if ($tag == 'like') {
                    $likes[$val[0]] = $val[1] ?? 0;
                } else if ($tag == 'comment') {
                    $comments[$val[0]] = $val[1] ?? 0;
                }
                if (isset($interactions[$val[0]])) {
                    $interactions[$val[0]] += $val[1] ?? 0;
                } else {
                    $interactions[$val[0]] = $val[1] ?? 0;
                }
                $totalInteractions += $val[1] ?? 0;
            }
        }
        $maxInterctionValue = count($interactions) > 0 ? max($interactions) : 0;
        $keyOfMaxInteraction = array_search($maxInterctionValue, $interactions);
        $results = [
            'comments' => $comments,
            'likes' => $likes,
            'max_interactions_on' => [
                'time' => $keyOfMaxInteraction,
                'value' => $maxInterctionValue
            ],
            'average_interactions_per_day' => round($totalInteractions / $lastDays, 2)
        ];
        return $results;
    }

    /**
     * distribution of interaction
     *
     * @param [type] $profileID
     * @param [type] $lastDays
     * @return void
     */
    public function instagramDistributionOfInteractions($profileID, $lastDays)
    {
        // executing a query will yield a resultset object
        $query = "SELECT SUM(value) FROM instagram_media_interactions WHERE account_id = '" . $profileID . "' and time > now() - " . $lastDays . "d GROUP BY interaction_type";
        $result = $this->database->query($query);
        // get the points from the resultset yields an array
        $series = $result->getSeries();
        $comments = 0;
        $likes = 0;
        $total = 0;
        foreach ($series as $seri) {
            if ($seri['tags']['interaction_type'] == 'comment')
            {
                $comments = $seri['values'][0][1];
            } else if ($seri['tags']['interaction_type'] == 'like')
            {
                $likes = $seri['values'][0][1];
            }
            $total += $seri['values'][0][1];
        }
        return [
            'comments' => [
                'value' => $comments,
                'percentage' => $total != 0 ? round($comments / $total, 2) : 0
            ],
            'likes' => [
                'value' => $likes,
                'percentage' => $total != 0 ? round($likes / $total, 2) : 0
            ],
        ];
    }

    /**
     * analytics interaction in day per 1k followers
     *
     * @param [type] $profileID
     * @param [type] $lastDays
     * @return void
     */
    public function analyticsInstagramInteractionInDayPer1KFollowers($profileID, $lastDays)
    {
        $totalFollowers = InstagramProfile::where('instagram_id', '=', $profileID)->first()->follower_count;
        $query = "SELECT (SUM(value) * 1000 / " . $totalFollowers . ") as value from instagram_media_interactions WHERE account_id = '" . $profileID . "' GROUP BY time(1d) FILL(0) tz('Asia/Saigon')";
        $result = $this->database->query($query);
        // get the points from the resultset yields an array
        $points = $result->getPoints();
        foreach ($points as &$point) {
            $point['value'] = round($point['value'], 2);
        }
        return $points;
    }
}