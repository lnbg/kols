<?php
namespace App\InfluxDB;

use DateTime;
use InfluxDB\Client as InfluxDBClient;

class InfluxDB {

    protected $influxDb;
    protected $database;

    public function __construct() 
    {
        $this->influxDb = new InfluxDBClient(env('INFLUXDB_HOST'), env('INFLUXDB_PORT'));
        $this->database = $this->influxDb->selectDB('analytics');
    }

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
        //$query = "SELECT last(value) FROM facebook_profile_fans WHERE profile_id = '" . $profileID . "' and time > now() - " . $lastDays . "d GROUP BY time(1d), deviceId TZ('Asia/Saigon')";
        $query = "SELECT * FROM facebook_profile_fans WHERE profile_id = '" . $profileID . "' and time > now() - " . $lastDays . "d";
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
                } else {
                    $currentPoint = $point['value'];
                    $maxFans = $point['value'];
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
     * count total posts in days with profile id
     * @param int $profileID
     * @return json
     */
    public function analyticsTotalPostInDaysByFacebookID($profileID, $lastDays)
    {
        // executing a query will yield a resultset object
        $query = "SELECT SUM(value) FROM facebook_profile_new_posts WHERE profile_id = '" . $profileID . "' and time > now() - " . $lastDays . "d GROUP BY time(1d), deviceId TZ('Asia/Saigon')";
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
        $query = "SELECT SUM(value) FROM facebook_profile_new_fan_posts WHERE profile_id = '" . $profileID . "' and time > now() - " . $lastDays . "d GROUP BY time(1d), deviceId TZ('Asia/Saigon')";
        $result = $this->database->query($query);
        // get the points from the resultset yields an array
        $points = $result->getPoints();
        return $points;
    }


    /**
     * count total media in days with instagram profile id
     * @param int $profileID
     * @return json
     */
    public function analyticsTotalMediaInDaysByInstagramID($profileID, $lastDays)
    {
        // executing a query will yield a resultset object
        $query = "SELECT * FROM instagram_profile_media WHERE profile_id = '" . $profileID . "' and time > now() - " . $lastDays . "d";
        $result = $this->database->query($query);
        // get the points from the resultset yields an array
        $points = $result->getPoints();
        return $points;
    }
    
}