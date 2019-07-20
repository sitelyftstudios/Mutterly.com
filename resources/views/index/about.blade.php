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
                        <h3>About Mutterly</h3>
                        <p>Whats the purpose of this website?</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="topWebsite about">
            <div class="innerTopWebsite">
                <div class="bottomFeed">
                    <p>Mutterly is a safe haven for people to come and express their thoughts anonymously to the world. It's also a way for you to get meaningful advice from other anonymous users from across the world! Express your thoughts, and we'll send you text notifications when someone reaches out to you</p>
                </div>
                <div class="secondBottomFeed">
                    <h5>How does it work?</h5>
                    <div class="inner">
                        <ol>
                            <li><p>You think the thoughts. Once you're on the site you create your own post that will be broadcasted on the websites front page</p></li>
                            <li><p>After you've written your thought, you'll provide your phone number which will allow you to receive updates about the post you created</p></li>
                            <li><p>Afterwards, you can sit back and relax and read all the comments that you get and the support you get</p></li>
                        </ol>
                        <hr>
                        <p>We also have a system in place to block negative and hurtful comments! And if we feel as if a post is too harmful we will hide it but give you the option to see the original and uncensored comment</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection