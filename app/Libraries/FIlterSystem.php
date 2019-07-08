<?php
namespace App\Libraries;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Libraries\User;

class FilterSystem
{
    private $db;
    private $response;

    /**
     * Construct function
     */
    public function __construct()
    {
        $this->db = new DB;
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
            // Dictionary of words
            $words = array('fuck', 'damn', 'fag', 'faggot', 'nigger', 'nigga', 'spick', 'nig', 'shit', 'asshole', 'fucktard', 'shitty', 'kill yourself', 'you deserve to die', 'u deserve to die', 'i hate you');

            // Iterate
            $lowercased = strtolower($text);

            $lowercased = str_replace($words, "****", $lowercased);

            // Return
            return $lowercased;
        }
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