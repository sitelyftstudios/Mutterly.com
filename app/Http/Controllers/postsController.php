<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

use App\Libraries\User;
use App\Libraries\PostSystem;

class postsController extends Controller
{
    private $psystem;

    // Construct
    public function __construct()
    {
        $this->psystem = new PostSystem();
    }

    /**
     * view
     * ---
     * This will allow someone to view the post
     */
    public function view($code)
    {
        if(!empty($code))
        {
            // Check
            if(count($post = $this->psystem->fetchThought($code)) == 1)
            {
                return view('thought', ['data' => json_encode($post)]);
            }else{
                return redirect('/');
            }
        }
    }

    /**
     * create
     * ---
     * This will create the post
     * 
     */
    public function create(Request $request)
    {
        // Validate this request
        $validation = Validator::make($request->all(), [
            'postText' => 'required|max:240',
            'postNumber' => 'required|max:10',
        ]);

        // Check 
        if(!$validation->fails())
        {
            // Now lets send the request over
            $create = json_decode($this->psystem->create($request->postText, $request->postNumber), true);

            // Reuturn val
            return response()->json($create);
        }else{
            return response()->json(['errors'=>$validation->errors()->all()]);
        }
        return false;
    }

    /**
     * commentCreate
     * ---
     * This will create a comment
     * 
     */
    public function comment(Request $request)
    {
        // Validate this request
        $validation = Validator::make($request->all(), [
            'commentContent' => 'required|max:200',
            'postId' => 'required'
        ]);

        // We're good now
        if(!$validation->fails())
        {
            $create = json_decode($this->psystem->addComment($request->postId, $request->commentContent), true);

            // Return val
            return json_encode($create);
        }else{
            return response()->json(['errors'=>$validation->errors()->all()]);
        }

        return false;
    }

    /**
     * share
     * ---
     * This will allow people to share posts
     */
    public function share(Request $request)
    {

    }

    /**
     * like
     * ---
     * This will allow a person to like a post
     */
    public function like(Request $request)
    {
        // Validate this request
        $validation = Validator::make($request->all(), [
            'postId' => 'required'
        ]);

        // We're good now
        if(!$validation->fails())
        {
            $create = json_decode($this->psystem->addLike($request->postId), true);

            // Return val
            return  json_encode($create);
        }else{
            return response()->json(['errors'=>$validation->errors()->all()]);
        }

        return false;
    }

    /**
     * fetchAll
     * --- 
     * This will fetch all of the posts for the homepage
     */
    public function fetchAll(Request $request)
    {
        // Validate this request
        $validation = $request->validate([
            'postId' => 'required'
        ]);

        // We're good now
        if(!$validation->fails())
        {
            $create = json_decode($this->psystem->fetchAllThoughts($request->postId), true);

            // Return val
            echo json_encode($create);
        }else{
            return response()->json(['errors'=>$validation->errors()->all()]);
        }

        return false;
    }
}
