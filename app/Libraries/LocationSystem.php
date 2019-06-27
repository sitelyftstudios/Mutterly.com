<?php
namespace App\Libraries;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use GeoIp2\Database\Reader;

class LocationSystem
{
    private $db;
    private $response;

    private $reader;

    /**
     * Construct function
     */
    public function __construct()
    {
        $this->db = new DB;
        
    }

    /**
     * GetIP
     * ---
     * Gets the current IP address
     */
    public function getIP()
    {
        if(!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            // IP from internet
            return $_SERVER['HTTP_CLIENT_IP'];
        }else if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            // IP from proxy
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    /**
     * GetIpData
     * ---
     * This will allow the software to get city/country
     */
    public function getInfo($ip)
    {
        $json = file_get_contents('http://getcitydetails.geobytes.com/GetCityDetails?fqcn='. $ip); 
        return json_decode($json);
    }
}