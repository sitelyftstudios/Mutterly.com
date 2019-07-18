<?php
$stylesheet = "index";

use App\Libraries\PostSystem;
use App\Libraries\LocationSystem;
use App\Libraries\FilterSystem;

$postSystem = new PostSystem();
$location = new LocationSystem();
$filter = new FilterSystem();

$post = json_decode($data, true)[0];

// Share content
$share_content = "
    <input type='text' id='copyFrom' value='http://mutterly.com/thought/".$post['thought_id']."' style='display: none;'/>
    <p style='margin-bottom: 5px;border-bottom: 1px solid #eee;padding-bottom: 10px;'><button class='copy btn-sm' style='color: #673ab7;' href='https://mutterly.com/thought/".$post['thought_id']."'>Copy link</button></p>
    <a style='font-size: 22px;' class='twitter-share-button' href='https://twitter.com/intent/tweet?text=Lets support our peer: https://mutterly.com/thought/".$post['thought_id']." #mutterly'><i class='fab fa-twitter'></i></a>

";
?>


<?php $__env->startSection('content'); ?>
    <div class="website-inner">
        <div class="topBanner">
            <div class="cover">
                <div class="topPostArea">
                    <h3>Show support</h3>
                    <p>Start a conversaiton with your peer below, give them advice and figure out solutions for them. They will see the support you give so please be constructive and nice!</p>
                </div>
                <div class="bottomPostArea">
                    <?php

                    if(1 == 1)
                    {

                        // IP
                        $local = $location->getInfo($post['thought_ip']);
                        $city = "";

                        if($post['thought_ip'] != "127.0.0.1")
                        {
                            if(!empty($local->geobytesregion))
                            {
                                // Lets update the city in db
                                DB::table('thoughts')->where('thought_id', $post['thought_id'])->update(['thought_state' => $local->geobytesregion]);

                                $city = $local->geobytesregion;
                            }else{
                                $city = $post['thought_state'];
                            }
                        }else{
                            $city = "Ohio"; 
                        }

                        // Likes
                        $likes = $postSystem->fetchLikes($post['thought_id']);
                    
                        // Comments
                        $comments = json_decode($postSystem->fetchComments($post['thought_id']), true);
        
                    ?>
                    <div class="post card">
                        <div class="post-mold">
                            <div class="innerPost">
                                <div class="topPost">
                                    <p class="mainPostText"><?php echo $filter->santitize($post['thought_content']); ?></p>
                                    <div class="actions">
                                        <?php if(strpos($filter->santitize($post['thought_content']), '*') !== false){ ?>
                                            <button type="button" style="background: #eb4d4b;" class="btn btn-sm showOriginalBtn" data-original="<?php echo $post['thought_content']; ?>">Show original</button>
                                        <?php } ?>
                                        <button type="button"  class="btn btn-sm" data-html="true" data-placement="top" data-toggle="popover" title="Share this thought" data-content="<?php echo $share_content; ?>" href="/thought/<?php echo $post['thought_id']; ?>">Share</button>
                                        <button style='display: none;' type="button" class="btn btn-sm reportBtn" id="reportBtn" data-code="<?php echo $post['thought_id']; ?>">Report</button>

                                        <a class="likeBtn" id="likeBtn-<?php echo $post['thought_id']; ?>" data-id="<?php echo $post['thought_id']; ?>"><span class="icon animated" id="icon-<?php echo $post['thought_id']; ?>"><i class="far fa-heart"></i></span> <span class="count" id="count-<?php echo $post['thought_id']; ?>"><?php echo count($likes); ?></span></a>
                                    </div>
                                </div>
                                <div class="bottomPost">
                                    <div class="bottomArea">
                                        <span><?php echo $city; ?></span> <span style="float: right;"><?php echo $filter->dateFix($post['thought_date']); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                  </div>
                </div>
            </div>
        <div class="topWebsite">
            <div class="innerTopWebsite">
                <div class="top">
                    <h3>Comments (<?php echo count($comments); ?>)</h3>
                    <div class="commentPostEntry">
                        <form id="commentMaker" action="<?php echo e(route('posts.comment')); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <textarea name="commentContent" id="commentContent" placeholder="Any advice?"></textarea>
                            <input type="hidden" id="postId" name="postId" value="<?php echo $post['thought_id']; ?>" />
                            <input type="submit" name="" class="btn btn-sm" value="Comment" />
                        </form>
                    </div>
                </div>
                <div class="bottomFeedView card-columns">
                    <?php
                        foreach($comments as $comment)
                        {
                            // Get variables
                            $comment_id = $comment['comment_id'];
                            $comment_content = $comment['comment_content'];
                            $comment_user_ip = $comment['comment_user_ip'];
                            $comment_date = $comment['comment_date'];
                            $comment_state = $comment['comment_state'];

                            $thought_id = $comment['thought_id'];
                            $thought_author = $comment['thought_author'];

                            // IP
                            $local = $location->getInfo($comment_user_ip);
                            $city = "";

                            if($comment_user_ip != "127.0.0.1")
                            {
                                if(!empty($local->geobytesregion))
                                {
                                    // Lets update the city in db
                                    DB::table('thoughts_comments')->where('comment_id', $comment_id)->update(['comment_state' => $local->geobytesregion]);

                                    $city = $local->geobytesregion;
                                }else{
                                    $city = $comment_state;
                                }
                            }else{
                                $city = "Ohio";
                            }
                            ?>
                            <div class="post card">
                                <div class="post-mold">
                                    <div class="innerPost">
                                        <div class="topPost">                                                <?php
                                                if($filter->bullyFilter($comment_content) > 0)
                                                {
                                                    ?>
                                                        <p id="cmtContent<?php echo $comment_id; ?>"><i>*This is a possible hate comment*</i></p>
                                                        <button type="button" style="margin-top: 15px;color: white;background: #eb4d4b;" class="btn btn-sm showOriginalCmtBtn" data-cmtid="<?php echo $comment_id; ?>" data-original="<?php echo $comment_content; ?>">Show original</button>

                                                    <?php
                                                }else{
                                                ?>
                                                    <p id="cmtContent<?php echo $comment_id; ?>"><?php echo $filter->santitize($comment_content); ?></p>
                                                <?php
                                                }
                                                ?>
                                            <div class="actions">

                                            </div>
                                        </div>
                                        <div class="bottomPost">
                                            <div class="bottomArea">
                                                <span><?php echo $city; ?></span> <span style="float: right;"><?php echo $filter->dateFix($comment_date); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/beautifulfears/resources/views/thought.blade.php ENDPATH**/ ?>