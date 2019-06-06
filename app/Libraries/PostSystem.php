<?php
namespace App\Libraries;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Libraries\User;

class PostSystem
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
     * Get IP from user
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
     * Create Post
     * ---
     * Create a new post for the anonymous user
     * 
     * @var
     */
    public function create($text)
    {
        if(!empty($text) && !empty($ip))
        {
            if(strlen($text) <= '240')
            {
                // Then we're good to go
                $ip = $this->getIP();

                if(!empty($ip))
                {
                    // Create link
                    $code = encrypt(md5(salt())) . rand(10, 1000);

                    // Insert
                    $insert = DB::table('thoughts')->insert(['thought_id' => $code, 'thought_content' => $text, 'thought_ip' => $ip, 'thought_date' => date('y-m-d H:i:s'), 'thought_views' => 0]);

                    // Return stuff
                    return json_encode(array('code' => '1', 'status' => 'Successfully posted!', 'content' => ['thought_id' => $code, 'thought_content' => $text, 'thought_date' => 'Now']));
                }else{
                    return json_encode(array('code' => '0', 'status' => 'Error: Please try again!'));
                }
            }else{
                return json_encode(array('code' => '0', 'status' => 'Error: Your post has to be under 240 characters'));
            }
        }else{
            return json_encode(array('code' => '0', 'status' => 'Error: Please try again!'));
        }
    }

    /**
     * addView
     * ---
     * 
     */
    public function addView($code)
    {

    }

    public function fetchThought($code)
    {

    }
}
