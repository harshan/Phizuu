<?php
$posts = $commentObj->comments;
$nextId = $commentObj->next_message;

foreach ($posts as $post) {
    if($post->user_type == 'FB') {
        $user_type = 'FaceBook User';
    } elseif ($post->user_type == 'TW') {
        $user_type = 'Twitter User';
    } else {
        $user_type = 'Unknow User Type';
    }

    if ($post->reply_count == 0) {
        $replyText = 'No Replies';
        $toggleFunction = "";
    } else {
        $replyText = '<span class="rpl_count"><b>'.$post->reply_count.'</b></span>';
        $toggleFunction = "javascript: toggle_comments({$post->comment_id});";
    }
    ?>
<div id="post_<?php echo $post->comment_id ?>" class="row" >
  <div class="post_row" onclick="<?php echo $toggleFunction ?>">
      <div class="post_info">By <?php echo $post->user_name ?> - <?php echo $user_type ?> at <?php echo $post->timestamp ?> UTC - <?php echo $replyText ?> -
          <a href="#" onclick="javascript: return deleteComment(<?php echo $post->comment_id ?>,this);">Delete</a>
      </div>
      <?php if ($post->reply_count != 0) { ?>
      <div class="expand_btn">
          <img class="expand_img" src="../../../images/expand_tour.png"/>
      </div>
      <?php } ?>
      <div class="comment">
          <div><?php echo str_replace("\n", "<br/>", $post->comment); ?></div>
          <?php if ($post->image_attachment!=NULL) { ?>
          <div style="text-align: center; padding: 5px">
              <img alt="Attached Image" src ="<?php echo $post->image_attachment->uri ?>" style="border: 2px solid #CCCCCC"/>
          </div>
          <?php } ?>
      </div>
      

  </div>
  <div class="comment_div">

  </div>
</div>
<?php } ?>
<?php if($nextId != "") { ?>
  <div class="row load_more" >
      <div class="post_row" style="padding-top: 5px; height: 20px" onclick="javascript: loadMore(<?php echo $nextId ?>, this);">
          Load More..
      </div>
      <span class="next_id" style="display: none"><?php echo $nextId ?></span>
  </div>
<?php } ?>
