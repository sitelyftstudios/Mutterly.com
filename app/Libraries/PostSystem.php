<?php
namespace App\Libraries;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use App\Libraries\User;
use App\Libraries\LocationSystem;
use App\Libraries\FilterSystem;

use Twilio\Rest\Client;

class PostSystem
{
    private $db;
    private $response;

    private $tclient;
    public $filter;

    private $tkey;
    private $tauth;
    private $tnumber;

    private $location;

    /**
     * Construct function
     */
    public function __construct()
    {
        $this->db = new DB;
        $this->filter = new FilterSystem;

        // Api keys
        $keys = DB::table('twilo')->where('id', '2')->get();

        $this->tkey = $keys[0]->api_key;
        $this->tauth = $keys[0]->api_auth;
        $this->tnumber = $keys[0]->api_number;

        // Connect 
        $this->tclient = new Client($this->tkey, $this->tauth);

        // Location services
        $this->location = new LocationSystem;

    }

    /**
     * Create Post
     * ---
     * Create a new post for the anonymous user
     * 
     * @var
     */
    public function create($text, $phonenumber)
    {
        if(!empty($text))
        {
            if(strlen($text) <= '240')
            {
                // Then we're good to go
                $ip = $this->location->getIP();

                if(!empty($ip))
                {
                    // Create link
                    $code = 'thgt_' . md5(encrypt(md5(md5(rand(10, 1000)) . rand(10, 1000))) . rand(10, 1000));

                    // Validate phone number
                    $valid_number = filter_var($phonenumber, FILTER_SANITIZE_NUMBER_INT);

                    $valid_number = str_replace("-", "", $valid_number);
                    $valid_number = str_replace("+1", "", $valid_number);

                    if (strlen($valid_number) <= 10 || strlen($valid_number) >= 14) 
                    {
                        // Send text
                        $valid_number = "+1" . $valid_number;

                        // Message
                        $this->tclient->messages->create($valid_number, array('from' => $this->tnumber, 'body' => "From Mutterly:\nDon't worry, you're not alone. We will text you when someone comments on your post. Be sure to save our number!"));

                        // Link 
                        $this->tclient->messages->create($valid_number, array('from' => $this->tnumber, 'body' => "Link to your post:\nhttps://mutterly.com/thought/".$code.""));

                        // Insert
                        $insert = DB::table('thoughts')->insert(['thought_id' => $code, 'thought_content' => $text, 'thought_ip' => $ip, 'thought_date' => date('y-m-d H:i:s'), 'thought_views' => 0, 'thought_phone_number' => $valid_number]);

                        // Return stuff
                        return json_encode(array('code' => '1', 'link' => ''.$code.'', 'status' => 'Successfully posted!', 'content' => ['thought_id' => $code, 'thought_content' => $text, 'thought_date' => 'Now']));
                    }else{
                        return json_encode(array('code' => '0', 'status' => 'Error:  Invalid phone number!'));
                    }
                }else{
                    return json_encode(array('code' => '0', 'status' => 'Error: Please try again'));
                }
            }else{
                return json_encode(array('code' => '0', 'status' => 'Error: Your post has to be under 240 characters'));
            }
        }else{
            return json_encode(array('code' => '0', 'status' => 'Error: Please try again!'));
        }
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
            $ip = $this->location->getIP();

            // Check
            $author = 0;

            if($ip = $thought[0]->thought_ip)
            {
                // Means its the author 
                $author = 1;
            }

            // Now lets create a code
            $ccode = 'cmt_' . md5(encrypt(md5(rand(10, 1000))) . rand(10, 1000));

            // Send a text
            $this->tclient->messages->create($thought[0]->thought_phone_number, array('from' => $this->tnumber, 'body' => "From Mutterly:\nYou have a new comment!"));

            // Link
            $this->tclient->messages->create($thought[0]->thought_phone_number, array('from' => $this->tnumber, 'body' => "https://mutterly.com/thought/view/".$code.""));

            // Okay lets add it to the database
            $insert = DB::table('thoughts_comments')->insert(['thought_id' => $code, 'thought_author' =>$author, 'comment_id' => $ccode, 'comment_content' => $content, 'comment_user_ip' => $ip, 'comment_date' => date('y-m-d H:i:s')]);

            // All done
            return json_encode(array('code' => '1', 'status' => 'Successfully commented!', 'content' => ['thought_id' => $code, 'comment_id' => $ccode, 'comment_content' => $content, 'comment_date' => 'Now']));
        }else{
            return json_encode(array('code' => '0', 'status' => 'Error: Please try again!'));
        }
    }

    /**
     * addLikes
     * ---
     * Will get  the likes
     */
    public function addLike($code)
    {
        if(count($thought = $this->fetchThought($code)) == 1)
        {
            // Now we can get the IP
            $ip = $this->location->getIP();

            // Now lets create a code
            $ccode = 'cmt_' . md5(encrypt(md5(rand(10, 1000))) . rand(10, 1000));

            // Send a text
            //$this->tclient->messages->create($valid_number, array('from' => $this->tnumber, 'body' => "From Mutterly - Don't worry, you're not alone. Follow this https://mutterly.com/thought/view/".$code." to view your post and it's comments! We will also text you when someone comments on your post."));

            // Okay lets add it to the database
            $insert = DB::table('thought_likes')->insert(['thought_id' => $code, 'like_ip' => $ip]);

            // All done
            return json_encode(array('code' => '1', 'status' => 'Successfully liked!', 'like_count' => count($this->fetchLikes($code))));
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

    /**
     * fetchLikes
     * ---
     * This will fetch the comments
     * 
     */
    public function fetchLikes($code)
    {
        return DB::table('thought_likes')->where('thought_id', $code)->get();
    }
}
