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
        if(!empty($text))
        {
            if(strlen($text) <= '240')
            {
                // Then we're good to go
                $ip = $this->getIP();

                if(!empty($ip))
                {
                    // Create link
                    $code = 'thgt_' . encrypt(md5(md5(rand(10, 1000)) . rand(10, 1000))) . rand(10, 1000);

                    // Insert
                    $insert = DB::table('thoughts')->insert(['thought_id' => $code, 'thought_content' => $text, 'thought_ip' => $ip, 'thought_date' => date('y-m-d H:i:s'), 'thought_views' => 0]);

                    // Return stuff
                    return json_encode(array('code' => '1', 'status' => 'Successfully posted!', 'content' => ['thought_id' => $code, 'thought_content' => $text, 'thought_date' => 'Now']));
                }else{
                    return json_encode(array('code' => '0', 'status' => 'Error: Please try again! 2'));
                }
            }else{
                return json_encode(array('code' => '0', 'status' => 'Error: Your post has to be under 240 characters'));
            }
        }else{
            return json_encode(array('code' => '0', 'status' => 'Error: Please try again! 1'));
        }
    }

    /**
     * addView
     * ---
     * This will add a like to the post
     * 
     */
    public function addLike($code)
    {

    }

    /**
     * addComment
     * ---
     * This will add a comment to the post
     * 
     */
    public function addComment($code, $content)
    {
        if(count($thought = $this->fetchThought($code)) == 1)
        {
            // Now we can get the IP
            $ip = $this->getIP();

            // Check
            $author = 0;

            if($ip = $thought->thought_ip)
            {
                // Means its the author 
                $author = 1;
            }

            // Now lets create a code
            $ccode = 'cmt_' . encrypt(md5(salt() . rand(10, 1000))) . rand(10, 1000);

            // Okay lets add it to the database
            $insert = DB::table('thoughts_comments')->insert(['thought_id' => $code, 'thought_author' =>$author, 'comment_id' => $ccode, 'comment_content' => $content, 'comemnt_user_ip' => $ip, 'comment_date' => date('y-m-d H:i:s')]);

            // All done
            return json_encode(array('code' => '1', 'status' => 'Successfully commented!', 'content' => ['thought_id' => $code, 'comment_id' => $ccode, 'comment_content' => $content, 'comment_date' => 'Now']));
        }else{
            return json_encode(array('code' => '0', 'status' => 'Error: Please try again!'));
        }
    }

    /**
     * fetchAllThoughts
     * ---
     * This will gather all of the posts
     * 
     */
    public function fetchAllThoughts()
    {
        return DB::table('thoughts')->orderBy('thought_id', 'desc')->get();
    }

    /**
     * fetchThought
     * ---
     * This will fetch the comment
     * 
     */
    public function fetchThought($code)
    {
        return DB::table('thoughts')->where('thought_id', $code)->get();
    }

    /**
     * fetchComment
     * ---
     * This will fetch the comments
     * 
     */
    public function fetchComments($code)
    {
        return DB::table('thoughts_comments')->where('thought_id', $code)->get();
    }
}
