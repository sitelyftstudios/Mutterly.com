<?php
$stylesheet = "index";

use App\Libraries\PostSystem;
use App\Libraries\LocationSystem;

$postSystem = new PostSystem();
$location = new LocationSystem();
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
                    $post = json_decode($data, true)[0];

                    if(1 == 1)
                    {

                        // IP
                        $local = $location->getInfo($post['thought_ip']);
                        $city = "";

                        if(!empty($local->geobytesregion))
                        {
                            // Lets update the city in db
                            DB::table('thoughts')->where('thought_id', $post['thought_id'])->update(['thought_state' => $local->geobytesregion]);

                            $city = $local->geobytesregion;
                        }else{
                            $city = $post['thought_state'];
                        }

                        // Likes
                        $likes = $postSystem->fetchLikes($post['thought_id']);
                    
                        // Comments
                        $comments = $postSystem->fetchComments($post['thought_id']);
        
                    ?>
                    <div class="post card">
                        <div class="post-mold">
                            <div class="innerPost">
                                <div class="topPost">
                                    <p><?php echo $post['thought_content']; ?></p>
                                    <div class="actions">
                                        <a href="/thought/<?php echo $post['thought_id']; ?>">Share</a>
                                        <a class="likeBtn" id="likeBtn-<?php echo $post['thought_id']; ?>" data-id="<?php echo $post['thought_id']; ?>"><span class="icon animated" id="icon-<?php echo $post['thought_id']; ?>"><i class="far fa-heart"></i></span> <span class="count" id="count-<?php echo $post['thought_id']; ?>"><?php echo count($likes); ?></span></a>
                                    </div>
                                </div>
                                <div class="bottomPost">
                                    <div class="bottomArea">
                                        <span><?php echo $city; ?></span> <span style="float: right;"><?php //echo timeago($thought_date); ?></span>
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
                </div>
                <div class="bottomFeed card-columns">
                    
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.index', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/beautifulfears/resources/views/thought.blade.php ENDPATH**/ ?>