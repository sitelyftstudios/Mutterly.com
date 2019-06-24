<?php
$stylesheet = "index";

use App\Libraries\PostSystem;

$postSystem = new PostSystem();
?>
@extends('layouts.index')

@section('content')
    <div class="container website-inner">
        <div class="topWebsite">
            <div class="innerTopWebsite">
                <div class="topPostArea">
                    <h3>Mutterly</h3>
                    <p>Mutterly is a safe haven for people to come and express their thoughts anonymously to the world. It's also a way for you to get meaningful advice from anonymous users from across the world!</p>
                </div>
                <div class="bottomPostArea">
                    <!-- Success -->
                    <div class="modal" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Modal title</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Modal body text goes here.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form -->
                    <form action="{{ route('posts.create') }}" method="post" id="postMakerForm">
                        <textarea name="postText" id="postText" placeholder="Whats on your mind?"></textarea>
                        <input type="submit" value="Post" />
                    </form>
                </div>
            </div>
        </div>
        <div class="bottomFeed">
            <?php
             $posts = $postSystem->fetchAllThoughts();

             foreach($posts as $post)
             {
                 // Vars
                 $id = $post->id;
                 $thought_id = $post->thought_id;
                 $thought_ip = $post->thought_ip;
                 $thought_content = $post->thought_content;
                 $thought_date = $post->thought_date;
                 $thought_views = $post->thought_views;

                 // Comments
                 $comments = $postSystem->fetchComments($thought_id);

                 ?>
                    <div class="post">
                        <div class="innerPost">
                            <div class="topPost">
                                <p><?php echo $thought_content; ?></p>
                            </div>
                            <div class="bottomPost">
                                <div class="bottomArea">
                                    <a href=""><i class="fas fa-comments"></i> <?php echo count($comments); ?></a> &middot; <span><i class="fas fa-eye"></i> <?php echo $thought_views; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                 <?php
             }
            ?>
        </div>
    </div>
@endsection