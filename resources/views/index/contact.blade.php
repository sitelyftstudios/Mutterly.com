<?php
$stylesheet = "index";

use App\Libraries\PostSystem;
use App\Libraries\LocationSystem;

$postSystem = new PostSystem();
$location = new LocationSystem();
?>
@extends('layouts.index')

@section('content')
    <div class="website-inner">
        <div class="topBanner">
            <div class="cover">
                <div class="topPostArea">
                        <h3>Whats on your mind?</h3>
                        <p>Mutterly is a safe haven for people to come and express their thoughts anonymously to the world. It's also a way for you to get meaningful advice from other anonymous users from across the world! Express your thoughts, and we'll send you text notifications when someone reaches out to you</p>
                    </div>
                    <div class="bottomPostArea">
                        <!-- Success -->
                        <div class="modal" tabindex="-1" role="dialog" id="successModal">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Success!</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Thanks for sharing your thoughts! Sometimes we hold things in and allow it to fester inside of us. This is a platform that allows you to get those thoughts out of your mind and into the world without having to worry about being judged</p>
                                    <p class="bottom">
                                        If you're feeling suicidal or thinking of harming yourself, please call: <a href="tel:1-800-273-8255">1-800-273-8255</a>
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn close-btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                                </div>
                            </div>
                        </div>
    
                        <!-- Form -->
                        <form action="{{ route('posts.create') }}" method="post" id="postMakerForm">
                            <textarea name="postText" id="postText" placeholder="Whats on your mind?" max="240"></textarea>
                            <input type="tel" name="postNumber" id="postNumber" placeholder="Phone Number" /> 
                            <input type="submit" class="btn btn-success" value="Post" />
                        </form>
                    </div>
            </div>
        </div>
        <div class="topWebsite">
            <div class="innerTopWebsite">
                <div class="bottomFeed card-columns">
                    
                </div>
            </div>
        </div>
    </div>
@endsection