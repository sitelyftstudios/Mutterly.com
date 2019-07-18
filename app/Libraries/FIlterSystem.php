<?php
namespace App\Libraries;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use Snipe\BanBuilder\CensorWords;

use App\Libraries\User;

class FilterSystem
{
    private $db;
    private $censor;

    private $response;

    /**
     * Construct function
     */
    public function __construct()
    {
        $this->db = new DB;
        $this->censor = new CensorWords;
    }

    /**
     * Santitize
     * ---
     * This will filter out stuff
     * 
     */
    public function santitize($text)
    {
        if(!empty($text))
        {
            // Satitize
            $string = $this->censor->censorString($text);

            // Return
            return $string['clean'];
        }
    }

    /**
     * Bully filter
     */
    public function bullyFilter($text)
    {
        $phrases = array('Die', 'die', 'i hope you die', 'kill yourself', 'nobody loves you', 'i hate you', 'I hate you', 'Nobody loves you', 'Youre worthless', "You're worthless", "Your ugly", 'Your worthless', 'Your stupid', 'Your dumb', 'You dont matter', 'You will never be happy', 'I hope you die', 'I am going to kill you', 'I will kill you');
        $fix = str_ireplace($phrases, "****", $text, $i);
        return $i;
    }

    /**
     * Timeago
     * ---
     * Will show a timeago format
     */
    public function dateFix($datetime, $full = false) {
        $now = new \DateTime;
        $ago = new \DateTime($datetime);
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
    
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}